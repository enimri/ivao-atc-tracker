# IVAO ATC Tracker

## Description

The IVAO ATC Tracker plugin allows you to display online ATCs at specific airports and fetch their METAR information from the IVAO network. You can manage which ATCs to track through a simple backend interface in WordPress. The plugin retrieves real-time data from IVAO's tracker API and displays it on your WordPress site using a shortcode.

## Features

- **Track Specific ATCs:** Add or remove ATC callsigns to track specific controllers.
- **Real-Time Data:** Fetches real-time ATC data from IVAO, including frequency, time online, and METAR information.
- **Easy Integration:** Use a simple shortcode to display the data on any page or post.

## Installation

1. **Upload the Plugin:**
   - Download the plugin files and upload them to the `/wp-content/plugins/ivao-atc-tracker/` directory of your WordPress site.

2. **Activate the Plugin:**
   - Go to the "Plugins" section in the WordPress admin panel and activate the "IVAO ATC Tracker" plugin.

3. **Configure Settings:**
   - Navigate to `Settings > IVAO ATC Tracker` in the WordPress admin panel.
   - Add the callsigns of the ATCs you want to track. These callsigns will be used to filter and display the relevant ATC data.

## Usage

To display the tracked ATCs on a page or post, use the following shortcode:

```shortcode
[ivao_atc_tracker]
