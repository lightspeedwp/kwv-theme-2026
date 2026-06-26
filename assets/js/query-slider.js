lsx_to.build_slider = function ( window_width ) {
// First slider: .lsx-to-slider
$(
 '.lsx-to-slider:not(.lsx-block-videos) .wp-block-post-template:not(.slider-disabled), ' +
'.lsx-to-slider:not(.lsx-block-videos) .wp-block-term-template:not(.slider-disabled)'
).each( function () {
const $this = $( this );
let slidesToShow = 3;

lsx_to.pre_build_slider( $this );

const str = $this.attr( 'class' ) || '';
const classRegex = /columns-\S*/g;
const matches = str.match( classRegex );
if ( matches && 0 < matches.length ) {
    const column = matches[ 0 ].split( '-' )[ 1 ];
    slidesToShow = column;
    }

if ( 1 < $this.children().length ) {
    $this.slick( {
        draggable: false,
        infinite: true,
        swipe: false,
        dots: true,
        slidesToShow, // Show 3 items at a time
        slidesToScroll: 1, // Scroll 1 item at a time
        autoplay: false,
        autoplaySpeed: 0,
        appendArrows: $this.parent(), // Ensure arrows are appended correctly
        appendDots: $this.parent(), // Append dots in the right container
        responsive:
            lsx_to.get_responsive_breakpoints( slidesToShow ),
            } );
        }
    } );

// Second slider: .lsx-to-slider.travel-information
$(
'.lsx-travel-information-wrapper.lsx-to-slider .travel-information:not(.slider-disabled)'
).each( function () {
    const $this = $( this );

    lsx_to.pre_build_slider( $this );

    // Ensure the second slider has 4 slides showing
    if ( 1 < $this.children().length ) {
        $this.slick( {
        draggable: false,
        infinite: true,
        swipe: false,
        dots: true,
        slidesToShow: 4, // Show 4 items at a time
        slidesToScroll: 1, // Scroll 1 item at a time
        autoplay: false,
        autoplaySpeed: 0,
        appendArrows: $this.parent(), // Ensure arrows are appended correctly for this slider
        appendDots: $this.parent(), // Append dots in the correct place
        responsive: [
            {
                breakpoint: 1028,
                settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                draggable: true,
                arrows: true,
                swipe: true,
                dots: true,
                },
            },
            {
                breakpoint: 782,
                settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                draggable: true,
                arrows: true,
                swipe: true,
                dots: true,
                },
            },
            ],

    } );

$this.on( 'init', function ( event, slick ) {
        if ( typeof toModalBootstrap === 'function' ) {
            toModalBootstrap();
            }
        } );
    }
 } );

lsx_to.build_video_slider();
};