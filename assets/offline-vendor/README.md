# Offline frontend assets

These pinned frontend assets are served from this project so Aneka Dharma pages
do not depend on a public CDN for their UI libraries.

| Asset | Version | License |
|---|---:|---|
| Bootstrap | 5.2.3 | MIT (`licenses/bootstrap-MIT.txt`) |
| DataTables | 1.11.4 | MIT; notice in the distributed JavaScript |
| DataTables FixedColumns | 4.2.1 | MIT; notice in the distributed JavaScript |
| Font Awesome Free | 6.4.2 | MIT / CC BY 4.0 / SIL OFL 1.1 (`licenses/fontawesome-free-6.4.2.txt`) |
| jQuery | 2.2.3, 3.3.1, 3.5.1 | MIT (`licenses/jquery-MIT.txt`) |
| Select2 | 4.1.0-rc.0 | MIT (`licenses/select2-MIT.txt`) |
| Select2 Bootstrap theme | 0.1.0-beta.10 | MIT (`licenses/select2-bootstrap-theme-MIT.txt`) |
| Source Sans Pro | Google Fonts v24 files | SIL OFL 1.1 (`licenses/source-sans-pro-OFL.txt`) |
| SweetAlert2 | 11.26.25 | MIT (`licenses/sweetalert2-MIT.txt`) |
| Swiper | 11.2.10 | MIT (`licenses/swiper-MIT.txt`) |
| Marhey and Quicksand | Google Fonts files | SIL OFL 1.1 (respective files in `licenses/`) |
| Ionicons caret SVG paths | 7.1.0 | MIT (`licenses/ionicons-MIT.txt`) |
| html5shiv / Respond | 3.7.3 / 1.4.2 | MIT |

The existing local Font Awesome 4.7.0 and Ionicons 2.0.0 assets are reused from
`assets/adminlte/bower_components`.

This only localizes static UI dependencies. Public links, remote images, video
thumbnails, and social content remain online-only; they may not load without
internet access.
