# Google Reviews Module for Joomla

![Joomla Version](https://img.shields.io/badge/Joomla-5.x-green.svg?style=flat-square)
![License](https://img.shields.io/badge/license-GPL--2.0%2B-blue.svg?style=flat-square)

The **Google Reviews** module for Joomla allows you to fetch and display reviews from Google Places directly on your Joomla site. This module is easy to configure and offers customization options for displaying reviews in a user-friendly format.

---

## Features

- Fetch reviews from a Google Place using the Google Places API.
- Display reviews in a responsive, card-based design.
- Show author information, ratings (as stars), and review dates.
- Customize maximum review text length via module settings.
- Fully localizable and compatible with multilingual sites.
- Lightweight and efficient, with caching support.

---

## Installation

1. Download the ZIP file containing the module.
2. Log in to your Joomla administrator panel.
3. Navigate to `Extensions > Install`.
4. Upload and install the module ZIP file.

---

## Configuration

1. Go to `Extensions > Modules` and locate **Google Reviews**.
2. Open the module settings and configure:
   - **Google API Key**: Enter your Google API Key (required).
   - **Place ID**: Enter the Place ID for the location you want to fetch reviews from (required).
   - **Cache Lifetime**: Set how long data should be cached (in minutes).
   - **Maximum Review Length**: Customize the maximum number of characters displayed for each review.
3. Assign the module to a position in your template.
4. Save the configuration and preview your site.

---

## Requirements

- Joomla 5.x or later
- A valid Google Places API key
- PHP 8.1 or later

---

## Localization

This module supports localization. To add translations:

1. Navigate to the `language` folder of your Joomla installation.
2. Add translation strings to the corresponding language files:
   - `en-GB.mod_google_reviews.ini`
   - `fr-FR.mod_google_reviews.ini`

---

## Customization

The module provides customizable layouts and styles:
- You can edit the layout in `tmpl/default.php`.
- Adjust CSS styles in your template or override them directly in `default.php`.

---

## Support

If you encounter any issues or have questions about this module, please feel free to open an issue or contact the developer.

---

## License

This module is licensed under the **GNU General Public License v2 or later**.

---
