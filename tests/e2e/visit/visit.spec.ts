import {
  test,
  expect,
  ALLOW_FORM_SUBMIT,
  FORM_TEST_EMAIL,
  TEST_MESSAGE,
  enquiryForm,
  futureDate,
  publishedEvents,
} from '../fixtures';
import type { Page } from '@playwright/test';

/**
 * Visit Us — venues, menus, events and bookings.
 * Pack: test-packs/launch-coverage-2026-10-22.md (VIS-*, EVT-*, BOOK-*)
 *
 * Each venue is a single-slide Carousel Block (`.kwv-venue-carousel`): slide 1 is
 * the venue, later slides are its tours/tastings. Every slide repeats the venue
 * name as its heading; the subheading and copy change per slide. Arrows only — no
 * dots, autoplay or loop. Each venue's "Menu" opens its own Popup Maker popup.
 */
const VENUES = [
  { name: 'Emporium', popup: /KWV Emporium/, book: '/visit/kwv', slides: 4, title: /emporium/i },
  { name: 'Cathedral Cellar', popup: /Cathedral Cellar/, book: '/visit/cellar', slides: 3, title: /cathedral cellar/i },
  { name: 'House of Fire', popup: /House of Fire/, book: '/visit/fire', slides: 3, title: /house of fire/i },
];

function venueCarousel(page: Page, name: string) {
  return page
    .locator('.kwv-venue-carousel')
    .filter({ has: page.getByRole('heading', { level: 2, name, exact: true }) });
}

test.describe('Visit Us @visit', () => {
  // VIS-TC1 — venue carousels
  for (const v of VENUES) {
    test(`VIS-TC1 ${v.name} carousel steps through its slides with arrows`, async ({ page }) => {
      await page.goto('/visit/');
      const carousel = venueCarousel(page, v.name);
      await expect(carousel).toHaveCount(1);

      const slides = carousel.locator('.swiper-slide');
      await expect(slides).toHaveCount(v.slides);
      // Every slide can be booked, and books the right venue.
      for (let i = 0; i < v.slides; i++) {
        await expect(slides.nth(i).getByRole('link', { name: /^book$/i })).toHaveAttribute(
          'href',
          new RegExp(`${v.book}/?$`),
        );
      }

      const active = carousel.locator('.swiper-slide-active');
      const prev = carousel.getByRole('button', { name: /previous slide/i });
      const next = carousel.getByRole('button', { name: /next slide/i });
      await expect(active).toHaveAttribute('aria-label', `1 / ${v.slides}`);
      await expect(prev).toBeDisabled();
      // No pagination dots.
      await expect(carousel.locator('.swiper-pagination-bullet')).toHaveCount(0);

      // No autoplay: the first slide is still active after an idle pause.
      await page.waitForTimeout(6_000);
      await expect(active).toHaveAttribute('aria-label', `1 / ${v.slides}`);

      const firstCopy = await active.innerText();
      await next.click();
      await expect(active).toHaveAttribute('aria-label', `2 / ${v.slides}`);
      await expect(active).toBeInViewport();
      await expect(prev).toBeEnabled();
      // Same venue heading, different subheading and copy.
      await expect(active.getByRole('heading', { level: 3 })).toHaveText(v.title);
      expect(await active.innerText()).not.toEqual(firstCopy);

      // No loop: the last slide disables Next.
      for (let i = 2; i < v.slides; i++) await next.click();
      await expect(active).toHaveAttribute('aria-label', `${v.slides} / ${v.slides}`);
      await expect(next).toBeDisabled();
    });
  }

  // VIS-TC2 — each venue's Menu opens that venue's popup
  for (const v of VENUES) {
    test(`VIS-TC2 ${v.name} "Menu" opens its own menu popup`, async ({ page }) => {
      await page.goto('/visit/');
      const carousel = venueCarousel(page, v.name);
      await carousel.locator('.swiper-slide-active').getByText('Menu', { exact: true }).click();

      const popup = page.locator('.pum-container').filter({ visible: true });
      await expect(popup).toHaveCount(1, { timeout: 20_000 });
      await expect(popup).toContainText(v.popup);

      await popup.getByRole('button', { name: /close/i }).click();
      await expect(popup).toHaveCount(0);

      // Reopen, then dismiss with Escape.
      await carousel.locator('.swiper-slide-active').getByText('Menu', { exact: true }).click();
      await expect(popup).toHaveCount(1);
      await page.keyboard.press('Escape');
      await expect(popup).toHaveCount(0);
    });
  }

  // VIS-TC3 — Upcoming Events only renders when there are events
  test('VIS-TC3 Upcoming Events renders only when events are published', async ({ page, request }) => {
    const events = await publishedEvents(request);
    await page.goto('/visit/');
    const heading = page.getByRole('heading', { name: /upcoming events/i });

    if (events.length === 0) {
      await expect(heading).toHaveCount(0);
      await expect(page.getByText(/no (upcoming )?events/i)).toHaveCount(0);
      return;
    }
    await expect(heading).toBeVisible();
    for (const e of events.slice(0, 4)) {
      await expect(page.locator(`a[href="${e.link}"]`).first()).toBeVisible();
    }
  });

  // EVT-TC1 / EVT-TC2 — single event page and Book now routing
  test('EVT-TC1/TC2 single events show one Book now that routes to the right booking form', async ({ page, request }) => {
    const events = await publishedEvents(request);
    test.skip(events.length === 0, 'No published kwv_event posts on the target environment.');

    for (const e of events.slice(0, 3)) {
      await page.goto(e.link);
      await expect(page.getByRole('heading', { level: 1 })).toBeVisible();

      // "Accommodation nearby" is optional, but never renders empty.
      const accommodation = page.getByRole('heading', { name: /accommodation nearby/i });
      if (await accommodation.count()) {
        await expect(page.locator('.wp-block-kwv-event-accommodation a').first()).toBeVisible();
      }

      const book = page.getByRole('link', { name: /book now/i });
      await expect(book, `exactly one Book now on ${e.link}`).toHaveCount(1);
      const href = (await book.getAttribute('href')) ?? '';
      expect(href).toMatch(/\/visit\/(kwv|cellar|fire|event-enquiry)\/?\?.*event=/);

      // The booking form arrives with the event pre-filled.
      await book.click();
      const form = enquiryForm(page);
      await expect(form).toBeVisible();
      const eventField = form
        .getByRole('textbox', { name: /^event/i })
        .or(form.getByRole('combobox', { name: /^event/i }));
      await expect(eventField).not.toHaveValue('');
    }
  });

  // BOOK-TC1 — venue booking forms
  for (const v of VENUES) {
    test(`BOOK-TC1 ${v.name} booking form validates and submits to its thank-you page`, async ({ page }) => {
      await page.goto(`${v.book}/`);
      const form = enquiryForm(page);
      await expect(form).toBeVisible();

      await form.getByRole('button', { name: /send message/i }).click();
      await expect(page.getByText(/there was a problem with your submission/i).first()).toBeVisible();

      test.skip(!ALLOW_FORM_SUBMIT || !FORM_TEST_EMAIL, 'Set KWV_ALLOW_FORM_SUBMIT=1 and KWV_FORM_TEST_EMAIL to submit.');

      const valid = enquiryForm(page);
      await valid.getByRole('textbox', { name: /^name/i }).fill('E2E Test');
      await valid.getByRole('textbox', { name: /^e-?mail/i }).fill(FORM_TEST_EMAIL);
      await valid.getByRole('textbox', { name: /^date/i }).fill(futureDate(30));
      await page.keyboard.press('Escape'); // close the date picker
      await valid.getByRole('spinbutton', { name: /hours/i }).fill('11');
      await valid.getByRole('spinbutton', { name: /minutes/i }).fill('00');
      await valid.getByRole('spinbutton', { name: /number of people/i }).fill('2');
      await valid.getByRole('textbox', { name: /message/i }).fill(TEST_MESSAGE);
      await valid.getByRole('button', { name: /send message/i }).click();

      await expect(page).toHaveURL(new RegExp(`${v.book}/thank-you/?$`), { timeout: 45_000 });
    });
  }

  // BOOK-TC2 — generic event enquiry form
  test('BOOK-TC2 event enquiry form submits to its thank-you page', async ({ page }) => {
    test.skip(!ALLOW_FORM_SUBMIT || !FORM_TEST_EMAIL, 'Set KWV_ALLOW_FORM_SUBMIT=1 and KWV_FORM_TEST_EMAIL to submit.');

    await page.goto('/visit/event-enquiry/');
    const form = enquiryForm(page);
    await form.getByRole('textbox', { name: /^name/i }).fill('E2E Test');
    await form.getByRole('textbox', { name: /^e-?mail/i }).fill(FORM_TEST_EMAIL);
    await form.getByRole('textbox', { name: /^event/i }).fill('E2E test event');
    await form.getByRole('spinbutton', { name: /number of people/i }).fill('2');
    await form.getByRole('textbox', { name: /message/i }).fill(TEST_MESSAGE);
    await form.getByRole('button', { name: /send message/i }).click();

    await expect(page).toHaveURL(/\/visit\/event-enquiry\/thank-you\/?$/, { timeout: 45_000 });
  });
});
