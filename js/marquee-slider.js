jQuery(document).ready(function($) {
    if (!$('.marquee-slider').length) {
        console.log('Marquee Slider: No sliders found on the page.');
        return;
    }

    $('.marquee-slider').each(function(index) {
        var $slider = $(this);
        var sliderId = $slider.data('slider-id') || 'unknown';
        var $inner = $slider.find('.marquee-inner');
        var speed = parseInt($slider.data('speed')) || 50;
        var direction = $slider.data('direction') || 'left';
        var totalWidth = 0;
        var singleSetWidth = 0;
        var wrapperCount = 0;

        console.log('Marquee Slider ' + index + ' (ID: ' + sliderId + '): Initializing with speed ' + speed + ' and direction ' + direction);

        // Calculate widths
        function calculateWidths() {
            singleSetWidth = 0;
            totalWidth = 0;
            var $wrappers = $inner.find('.marquee-image-wrapper');
            wrapperCount = $wrappers.length;
            var imagesPerSet = Math.ceil(wrapperCount / 4); // Assume at least 4x duplication
            $wrappers.each(function(i) {
                var wrapperWidth = $(this).outerWidth(true);
                totalWidth += wrapperWidth;
                if (i < imagesPerSet) {
                    singleSetWidth += wrapperWidth;
                }
            });
            console.log('Marquee Slider ' + index + ' (ID: ' + sliderId + '): Single set width: ' + singleSetWidth + ', Total width: ' + totalWidth);
            return singleSetWidth > 0 && totalWidth > 0;
        }

        // Animation state
        var pos = direction === 'left' ? 0 : -singleSetWidth;

        function animate(timestamp) {
            if (!lastTime) lastTime = timestamp;
            var deltaTime = (timestamp - lastTime) / 1000;
            lastTime = timestamp;

            if (direction === 'left') {
                pos -= speed * deltaTime;
                if (pos <= -singleSetWidth) {
                    pos += singleSetWidth;
                    // Move first wrapper to end for seamless looping
                    $inner.append($inner.find('.marquee-image-wrapper').first());
                }
            } else {
                pos += speed * deltaTime;
                if (pos >= 0) {
                    pos -= singleSetWidth;
                    // Move last wrapper to start
                    $inner.prepend($inner.find('.marquee-image-wrapper').last());
                }
            }

            $inner.css('transform', 'translateX(' + pos + 'px)');

            requestAnimationFrame(animate);
        }

        // Wait for images to load
        var images = $inner.find('img');
        var loadedImages = 0;
        var lastTime = null;

        if (images.length === 0) {
            console.warn('Marquee Slider ' + index + ' (ID: ' + sliderId + '): No images found in slider.');
            return;
        }

        images.each(function(i) {
            if (this.complete && this.naturalWidth !== 0) {
                loadedImages++;
            } else {
                $(this).on('load', function() {
                    loadedImages++;
                    if (loadedImages === images.length) {
                        console.log('Marquee Slider ' + index + ' (ID: ' + sliderId + '): All images loaded, starting animation.');
                        if (calculateWidths()) {
                            $inner.css('width', 'auto');
                            pos = direction === 'left' ? 0 : -singleSetWidth;
                            requestAnimationFrame(animate);
                        } else {
                            console.warn('Marquee Slider ' + index + ' (ID: ' + sliderId + '): No valid image set width detected.');
                        }
                    }
                }).on('error', function() {
                    console.warn('Marquee Slider ' + index + ' (ID: ' + sliderId + '): Image failed to load: ' + $(this).attr('src'));
                    loadedImages++;
                    if (loadedImages === images.length) {
                        if (calculateWidths()) {
                            $inner.css('width', 'auto');
                            pos = direction === 'left' ? 0 : -singleSetWidth;
                            requestAnimationFrame(animate);
                        }
                    }
                });
            }
        });

        if (loadedImages === images.length) {
            console.log('Marquee Slider ' + index + ' (ID: ' + sliderId + '): Images already loaded, starting animation.');
            if (calculateWidths()) {
                $inner.css('width', 'auto');
                pos = direction === 'left' ? 0 : -singleSetWidth;
                requestAnimationFrame(animate);
            } else {
                console.warn('Marquee Slider ' + index + ' (ID: ' + sliderId + '): No valid image set width detected.');
            }
        }
    });
});