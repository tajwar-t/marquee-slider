jQuery(document).ready(function($) {
    if (!$('.marquee-slider').length) {
        console.log('Marquee Slider: No sliders found on the page.');
        return;
    }

    $('.marquee-slider').each(function(index) {
        var $slider = $(this);
        var sliderId = $slider.data('slider-id') || 'unknown';
        var direction = $slider.data('direction') || 'left';
        var $inner = $slider.find('.marquee-inner');
        var $images = $slider.find('img');
        var imageCount = $images.length;

        console.log('Marquee Slider ' + index + ' (ID: ' + sliderId + '): Found ' + imageCount + ' images, initializing with direction ' + direction);

        if (imageCount === 0) {
            console.warn('Marquee Slider ' + index + ' (ID: ' + sliderId + '): No images found in slider.');
            return;
        }

        // Wait for images to load
        var loadedImages = 0;
        $images.each(function() {
            if (this.complete && this.naturalWidth !== 0) {
                loadedImages++;
            } else {
                $(this).on('load', function() {
                    loadedImages++;
                    if (loadedImages === imageCount) {
                        initializeSlider($slider, sliderId, index);
                    }
                }).on('error', function() {
                    console.warn('Marquee Slider ' + index + ' (ID: ' + sliderId + '): Image failed to load: ' + $(this).attr('src'));
                    loadedImages++;
                    if (loadedImages === imageCount) {
                        initializeSlider($slider, sliderId, index);
                    }
                });
            }
        });

        if (loadedImages === imageCount) {
            initializeSlider($slider, sliderId, index);
        }
    });

    function initializeSlider($slider, sliderId, index) {
        var $inner = $slider.find('.marquee-inner');
        $inner.css({
            'animation-play-state': 'running',
            'display': 'inline-flex' // Ensure display is set
        });
        console.log('Marquee Slider ' + index + ' (ID: ' + sliderId + '): CSS animation initialized.');
    }
});