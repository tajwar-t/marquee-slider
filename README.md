# **Marquee Slider Plugin**

## **Description**

Marquee Slider is a lightweight WordPress plugin that allows administrators to create multiple marquee sliders with customizable images, speed, and direction (left or right). Each slider displays a continuous, seamless loop of images without resets or white spaces, using pure CSS animations to ensure smooth performance and minimize conflicts with themes or other plugins. Images are individually wrapped in `<div>` elements for flexible styling, and the plugin supports shortcode integration for easy placement on posts or pages.

This plugin is ideal for showcasing logos, product images, or other visuals in a scrolling marquee format, with a user-friendly admin interface for managing sliders.

## **Features**

* **Multiple Sliders**: Create unlimited sliders, each with a unique ID for use via shortcode.  
* **Customizable Settings**:  
  * **Images**: Select images from the WordPress Media Library.  
  * **Speed**: Set scrolling speed (10–200 pixels per second).  
  * **Direction**: Choose left or right scrolling direction.  
* **Seamless Looping**: Images are duplicated to ensure continuous scrolling without resets or white spaces.  
* **CSS-Based Animation**: Uses CSS `@keyframes` for reliable, smooth scrolling, avoiding JavaScript conflicts with themes.  
* **Responsive Design**: Images scale responsively with fixed height (200px) and automatic width.  
* **Lazy Loading**: Images load lazily to improve page performance.  
* **Debugging Support**: Console logs for initialization and image loading errors to aid troubleshooting.

## **Installation**

1. **Download and Install**:

   * Download the plugin zip file or clone the repository.  
   * Upload the `marquee-slider` folder to `wp-content/plugins/` via FTP, or use the WordPress admin panel to upload and install the zip file.  
   * Alternatively, place the following files in `wp-content/plugins/marquee-slider/`:  
     * `marquee-slider-plugin.php` (main plugin file)  
     * `css/marquee-slider.css` (frontend styles)  
     * `js/marquee-slider.js` (frontend JavaScript)  
     * `js/marquee-slider-admin.js` (admin JavaScript)  
     * `css/marquee-slider-admin.css` (admin styles)  
2. **Activate Plugin**:

   * In the WordPress admin panel, go to **Plugins \> Installed Plugins**.  
   * Locate **Marquee Slider** and click **Activate**.  
3. **Clear Caches**:

   * If using a caching plugin (e.g., WP Rocket, W3 Total Cache), clear the site cache.  
   * Clear your browser cache to ensure the latest plugin files (version 2.1) are loaded.

## **Usage**

1. **Configure Sliders**:

   * Navigate to **Marquee Slider** in the WordPress admin menu.  
   * Click **Add New** to create a new slider.  
   * Fill in the details:  
     * **Slider Name**: A descriptive name (e.g., "Logo Slider").  
     * **Images**: Click **Add Image** to select 3–5 images from the Media Library (recommended width ≥200px for visible scrolling).  
     * **Marquee Speed**: Enter a speed in pixels per second (10–200, default 50).  
     * **Direction**: Select **Left** or **Right** for the scrolling direction.  
   * Click **Create Slider** (or **Update Slider** if editing).  
   * Repeat to create additional sliders (e.g., one with direction set to "Right").  
   * Note the **Slider ID** (e.g., `slider_123`) from the settings table.  
2. **Add Sliders to Pages/Posts**:

   * Copy the shortcode for each slider from the settings table, e.g., `[marquee_slider id="slider_123"]`.  
   * Edit a post or page and paste the shortcode where you want the slider to appear.

Example for multiple sliders:  
\[marquee\_slider id="slider\_123"\]

\[marquee\_slider id="slider\_456"\]

*   
  * Save and publish the post/page.  
3. **Verify Display**:

   * View the page to ensure sliders scroll smoothly.  
   * Confirm the second slider moves right (if configured) and others move left.  
   * Check that each image is wrapped in a `<div class="marquee-image-wrapper">` (use browser developer tools, F12 \> Elements).  
   * Open the browser console (F12 \> Console) to verify logs:  
     * `Marquee Slider 0 (ID: slider_123): Found [n] images, initializing with direction left`  
     * `Marquee Slider 1 (ID: slider_456): Found [n] images, initializing with direction right`  
     * `Marquee Slider 1 (ID: slider_456): CSS animation initialized.`

## **Requirements**

* **WordPress**: Version 5.0 or higher.  
* **PHP**: Version 7.0 or higher.  
* **jQuery**: Included with WordPress core; no additional libraries required.  
* **Images**: At least 3–5 images per slider with a minimum width of 200px for optimal scrolling visibility.

## **Troubleshooting**

If sliders do not scroll or appear static:

* **Check Console Logs**:  
  * Open the browser developer tools (F12 \> Console).  
  * Look for errors like:  
    * `No images found in slider.`: Ensure images are selected for the slider in the admin panel.  
    * `Image failed to load: [URL]`: Verify images exist in the Media Library. Regenerate thumbnails using a plugin like "Regenerate Thumbnails".  
* **CSS Conflicts**:  
  * Inspect the slider HTML (F12 \> Elements) and confirm `.marquee-inner` has `animation: marquee var(--animation-duration) linear infinite` and a valid `--animation-duration` (e.g., `40s` for 50 px/s).

Check if theme CSS overrides `display`, `visibility`, or `animation`. Add the following to your theme’s `style.css` to resolve:  
.marquee-inner {

    animation: marquee var(--animation-duration) linear infinite \!important;

    display: inline-flex \!important;

}

*   
* **Image Issues**:  
  * Ensure each slider has 3–5 images with widths ≥200px.  
  * Verify image URLs are valid in the Media Library.  
* **JavaScript Conflicts**:  
  * Confirm `jquery` and `marquee-slider.js` load (F12 \> Network).  
  * Check for errors like `jQuery is not defined`. If present, ensure the theme doesn’t dequeue jQuery or load an incompatible version.  
* **Theme/Plugin Conflicts**:  
  * Switch to a default theme (e.g., Twenty Twenty-Five) to isolate theme issues.  
  * Deactivate other plugins, especially those affecting animations or jQuery (e.g., other sliders, optimizers).  
* **Browser Testing**:  
  * Test in Chrome, Firefox, and Safari. If animations lag in Firefox, ensure `will-change: transform` is applied (included in `marquee-slider.css`).  
* **Animation Check**:  
  * Temporarily set the slider speed to 100 px/s in the admin panel to confirm visible scrolling.  
  * Verify `data-direction="right"` on the second slider’s HTML for rightward movement.

## **Support**

For issues or feature requests, please provide:

* The active theme name and version.  
* A list of active plugins.  
* The shortcode used (e.g., `[marquee_slider id="slider_456"]`).  
* Number of images per slider and their approximate dimensions.  
* Any console errors/warnings (F12 \> Console).  
* Whether an Elementor widget version is preferred.

Contact the developer through the WordPress plugin support forum or your preferred channel.

## **Changelog**

### **Version 2.1**

* **Released**: July 14, 2025  
* **Updated**: Switched to CSS-based animation (`@keyframes marquee`) to eliminate JavaScript conflicts with themes.  
* **Improved**: Enhanced image loading checks to prevent static images.  
* **Fixed**: Ensured seamless looping with no resets or white spaces using image duplication.  
* **Added**: Lazy loading for images to improve performance.

### **Version 2.0**

* **Released**: June 29, 2025  
* **Updated**: Used custom JavaScript animation with `requestAnimationFrame` for scrolling.  
* **Fixed**: Addressed static image issues from earlier versions.

## **License**

This plugin is licensed under the GPL2 License. See the `marquee-slider-plugin.php` file for details.