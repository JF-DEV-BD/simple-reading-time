# Simple Reading Time

A lightweight, single-file WordPress plugin that calculates and displays the estimated reading time for blog posts. Zero bloat, no database queries, and developer-friendly filters.

## Features

* **Automatic Display:** Automatically prepends the estimated reading time to single blog posts.
* **Shortcode Support:** Place `[reading_time]` anywhere inside posts, pages, or widgets.
* **Smart Parsing:** Strips HTML tags and shortcodes before counting words for accuracy.
* **Developer Hooks:** Customize the words-per-minute (WPM) rate or HTML markup via WordPress filters.
* **Single-File Architecture:** Easy to audit, fork, or drop into any WordPress installation.

## Installation

1. Download or clone this repository into your WordPress plugins directory:
   ```bash
   cd wp-content/plugins/
   git clone [https://github.com/yourusername/simple-reading-time.git](https://github.com/yourusername/simple-reading-time.git)
