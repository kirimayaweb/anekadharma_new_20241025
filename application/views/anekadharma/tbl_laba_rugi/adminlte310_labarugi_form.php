<form action="<?php echo $action; ?>" method="post">


	<div class="content-wrapper">

		<head>
			<style>
				.customers-labarugi-table,
				#customers {
					font-family: Arial, Helvetica, sans-serif;
					border-collapse: collapse;
					width: 100%;
				}

				.customers-labarugi-table td,
				.customers-labarugi-table th,
				#customers td,
				#customers th {
					border: 0px solid #ddd;
					padding: 3px;
				}

				.customers-labarugi-table tr:nth-child(even),
				#customers tr:nth-child(even) {
					background-color: #f2f2f2;
				}

				.customers-labarugi-table tr:hover,
				#customers tr:hover {
					background-color: #ddd;
				}

				.customers-labarugi-table th,
				#customers th {
					padding-top: 1px;
					padding-bottom: 1px;
					background-color: white;
					color: black;
				}

				.labarugi-tabs-shell {
					margin: 12px 16px 20px;
					padding: 14px 14px 18px;
					border-radius: 14px;
					border: 2px solid rgba(57, 255, 136, 0.45);
					box-shadow: 0 0 18px rgba(57, 255, 136, 0.12), inset 0 0 0 1px rgba(57, 255, 136, 0.08);
					background: linear-gradient(180deg, #ffffff 0%, #f8fffb 100%);
					max-width: 100%;
					overflow-x: hidden;
					box-sizing: border-box;
				}

				.labarugi-tab-nav {
					display: flex;
					flex-wrap: wrap;
					gap: 10px;
					list-style: none;
					margin: 0 0 16px;
					padding: 0;
				}

				.labarugi-tab-nav li {
					list-style: none;
					margin: 0;
					padding: 0;
				}

				.labarugi-tab-btn {
					border: 2px solid #d7dee8;
					border-radius: 10px 10px 0 0;
					background: #f4f6f9;
					color: #5c6675;
					font-style: italic;
					font-weight: 400;
					padding: 11px 18px;
					cursor: pointer;
					transition: all 0.22s ease;
					box-shadow: inset 0 -2px 0 rgba(0, 0, 0, 0.03);
				}

				.labarugi-tab-btn:hover {
					background: #eef3ff;
					border-color: #b8c7e6;
				}

				.labarugi-tab-btn.is-active {
					background: linear-gradient(135deg, #0d6efd 0%, #1e88ff 45%, #0056d6 100%);
					border-color: #ffd54a;
					color: #ffe566;
					font-style: normal;
					font-weight: 700;
					text-shadow: 0 0 8px rgba(255, 229, 102, 0.55), 0 0 2px rgba(255, 255, 255, 0.35);
					box-shadow: 0 0 0 2px rgba(255, 213, 74, 0.85), 0 8px 22px rgba(13, 110, 253, 0.28);
				}

				.labarugi-tab-panel {
					display: none;
					animation: labarugiTabFade 0.28s ease;
				}

				.labarugi-tab-panel.is-active {
					display: block;
				}

				@keyframes labarugiTabFade {
					from { opacity: 0; transform: translateY(6px); }
					to { opacity: 1; transform: translateY(0); }
				}

				.labarugi-per-unit-wrap {
					border-top: 2px dashed rgba(57, 255, 136, 0.35);
					padding-top: 18px;
				}

				.labarugi-per-unit-heading {
					color: #1b5e20;
					font-weight: 700;
					margin-bottom: 14px;
				}

				.labarugi-unit-card {
					border: 1px solid #dce8df;
					border-left: 4px solid #39ff88;
					border-radius: 10px;
					padding: 12px 14px;
					margin-bottom: 14px;
					background: #fbfffc;
					box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
				}

				.labarugi-unit-card-title {
					font-weight: 700;
					margin-bottom: 10px;
					color: #174a2a;
				}

				.labarugi-unit-kode {
					display: inline-block;
					background: #e8f8ee;
					color: #1b5e20;
					border-radius: 6px;
					padding: 2px 8px;
					margin-right: 8px;
					font-size: 0.85em;
				}

				.labarugi-unit-grid-wrap {
					border: 1px solid rgba(57, 255, 136, 0.35);
					border-radius: 14px;
					background: linear-gradient(145deg, #fafffe 0%, #f0faf4 50%, #eef6ff 100%);
					box-shadow: 0 8px 28px rgba(23, 74, 42, 0.08);
					padding: 16px 14px 18px;
					width: 100%;
					max-width: 100%;
					box-sizing: border-box;
				}

				.labarugi-unit-grid-toolbar {
					display: flex;
					justify-content: flex-start;
					margin-bottom: 10px;
				}

				.labarugi-btn-setting-keterangan {
					background: linear-gradient(135deg, #1e88e5 0%, #1565c0 42%, #0d47a1 100%) !important;
					border: 2px solid #ffd54f !important;
					color: #ffffff !important;
					font-weight: 700;
					padding: 8px 16px;
					border-radius: 10px;
					box-shadow:
						0 0 16px rgba(255, 213, 79, 0.6),
						0 0 22px rgba(30, 136, 229, 0.45),
						0 0 8px rgba(255, 255, 255, 0.15) inset,
						0 4px 16px rgba(13, 71, 161, 0.45);
					text-shadow: 0 0 12px rgba(255, 255, 255, 0.95), 0 0 4px rgba(255, 255, 255, 0.55);
					transition: all 0.25s ease;
				}

				.labarugi-btn-setting-keterangan:hover,
				.labarugi-btn-setting-keterangan:focus {
					background: linear-gradient(135deg, #42a5f5 0%, #1e88e5 50%, #1565c0 100%) !important;
					border-color: #ffe082 !important;
					color: #ffd700 !important;
					text-shadow: 0 0 14px rgba(255, 215, 0, 0.98), 0 0 26px rgba(255, 193, 7, 0.8);
					box-shadow:
						0 0 20px rgba(255, 213, 79, 0.85),
						0 0 28px rgba(30, 136, 229, 0.55),
						0 0 10px rgba(255, 215, 0, 0.35) inset,
						0 6px 20px rgba(13, 71, 161, 0.5);
					outline: none;
				}

				#modalLabarugiKeterangan {
					z-index: 20060 !important;
				}

				#modalLabarugiKeterangan .labarugi-ket-modal-dialog {
					max-width: 1400px !important;
					width: 98vw !important;
					margin: 0.75rem auto;
				}

				#modalLabarugiKeterangan .modal-content {
					width: 100%;
				}

				#modalLabarugiKeterangan .modal-body {
					overflow: visible !important;
					max-height: none !important;
					padding: 14px 18px 18px;
				}

				.labarugi-ket-modal-dialog {
					max-width: 1400px;
					width: 98vw;
					margin: 0.75rem auto;
				}

				.labarugi-keterangan-modal .modal-body {
					padding: 14px 18px 18px;
					overflow: visible;
				}

				.labarugi-keterangan-modal .modal-header {
					background: linear-gradient(135deg, #1a5f3f, #2e8b57);
					color: #fff;
				}

				.labarugi-keterangan-modal .modal-header .close {
					color: #fff;
					opacity: 0.85;
				}

				.labarugi-ket-dt-shell {
					width: 100%;
				}

				.labarugi-ket-dt-wrap {
					width: 100%;
					overflow: visible;
					border: 2px solid #ffd54f;
					border-radius: 10px;
					background: #fff;
					box-shadow: 0 0 18px rgba(255, 213, 79, 0.55), inset 0 0 10px rgba(255, 235, 130, 0.12);
					padding: 0;
				}

				#modalLabarugiKeterangan .dataTables_wrapper {
					width: 100%;
					padding: 0;
				}

				#modalLabarugiKeterangan .labarugi-ket-dt-top {
					padding: 10px 14px 8px;
					background: #fffef8;
					border-bottom: 1px dashed rgba(255, 213, 79, 0.65);
				}

				#modalLabarugiKeterangan .labarugi-ket-dt-bottom {
					padding: 8px 14px 12px;
					background: #fffef8;
					border-top: 1px dashed rgba(255, 213, 79, 0.65);
				}

				#modalLabarugiKeterangan .dataTables_scroll {
					width: 100% !important;
				}

				#modalLabarugiKeterangan .dataTables_scrollHead,
				#modalLabarugiKeterangan .dataTables_scrollBody {
					width: 100% !important;
				}

				#modalLabarugiKeterangan .dataTables_scrollBody {
					overflow: auto !important;
				}

				#modalLabarugiKeterangan .dataTables_filter input {
					min-width: 180px;
				}

				#tblLabarugiKeterangan {
					width: 100% !important;
					table-layout: auto;
					margin-bottom: 0;
				}

				#tblLabarugiKeterangan thead th,
				#tblLabarugiKeterangan tbody td {
					white-space: normal;
					vertical-align: middle;
					word-break: break-word;
				}

				.labarugi-ket-col-id { min-width: 46px; width: 46px; }
				.labarugi-ket-col-urutan { min-width: 88px; width: 88px; text-align: center; }
				.labarugi-ket-urutan-cell {
					text-align: center;
					vertical-align: middle !important;
					white-space: nowrap;
				}
				.labarugi-ket-urutan-num {
					display: inline-block;
					min-width: 22px;
					font-weight: 700;
					color: #1a5f3f;
					margin-right: 4px;
					vertical-align: middle;
				}
				.labarugi-ket-btn-urutan-up {
					display: inline-flex;
					align-items: center;
					justify-content: center;
					width: 34px;
					height: 34px;
					padding: 0;
					border-radius: 8px;
					border: 2px solid #ffb300;
					background: linear-gradient(180deg, #ffe082 0%, #ffc107 45%, #ffb300 100%);
					color: #5d4037;
					box-shadow: 0 2px 6px rgba(255, 179, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.55);
					vertical-align: middle;
					transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
				}
				.labarugi-ket-btn-urutan-up .fa {
					font-size: 17px;
					font-weight: 700;
					line-height: 1;
					text-shadow: 0 1px 0 rgba(255, 255, 255, 0.45);
				}
				.labarugi-ket-btn-urutan-up:hover:not(:disabled),
				.labarugi-ket-btn-urutan-up:focus:not(:disabled) {
					background: linear-gradient(180deg, #ffecb3 0%, #ffd54f 50%, #ffc107 100%);
					border-color: #ff8f00;
					color: #3e2723;
					transform: translateY(-1px);
					box-shadow: 0 4px 10px rgba(255, 193, 7, 0.55), inset 0 1px 0 rgba(255, 255, 255, 0.65);
					outline: none;
				}
				.labarugi-ket-btn-urutan-up:disabled {
					opacity: 0.35;
					cursor: not-allowed;
					box-shadow: none;
				}
				.labarugi-ket-col-uuid { min-width: 120px; }
				.labarugi-ket-col-nama { min-width: 150px; }
				.labarugi-ket-col-group { min-width: 140px; }
				.labarugi-ket-col-status-ket { min-width: 115px; }
				.labarugi-ket-col-status-lr { min-width: 105px; }
				.labarugi-ket-col-ket { min-width: 120px; }
				.labarugi-ket-col-aksi { min-width: 88px; width: 88px; text-align: center; }

				.labarugi-ket-row-input {
					min-width: 100%;
					width: 100%;
					font-size: 0.85rem;
					box-sizing: border-box;
				}

				.labarugi-ket-row-select {
					min-width: 100%;
					width: 100%;
					font-size: 0.85rem;
					box-sizing: border-box;
				}

				.labarugi-ket-table tbody td {
					padding: 10px 8px;
				}

				.labarugi-ket-btn-save-row {
					font-size: 0.75rem;
					font-weight: 700;
					padding: 4px 8px;
				}

				.customers-labarugi-table-grid-mode {
					table-layout: fixed;
					width: 100% !important;
				}

				.labarugi-grid-host-wrap {
					display: block;
					width: 100%;
					max-width: 100%;
					margin: 12px 0 18px;
					box-sizing: border-box;
				}

				.labarugi-footer-table {
					margin-top: 8px;
				}

				.labarugi-unit-grid-header {
					margin-bottom: 12px;
					padding-bottom: 10px;
					border-bottom: 1px dashed rgba(57, 255, 136, 0.4);
				}

				.labarugi-unit-grid-scroll-shell {
					width: 100%;
					max-width: 100%;
				}

				.labarugi-unit-grid-scroll-top {
					display: block;
					width: 100%;
					max-width: 100%;
					overflow-x: auto;
					overflow-y: hidden;
					-webkit-overflow-scrolling: touch;
					border: 1px solid #dce8e0;
					border-bottom: none;
					border-radius: 10px 10px 0 0;
					background: #f7fbf8;
					height: 16px;
					margin-bottom: 0;
				}

				.labarugi-unit-grid-scroll-top-inner {
					height: 1px;
					pointer-events: none;
				}

				.labarugi-unit-grid-scroll-main.labarugi-unit-grid-scroll {
					border-radius: 0 0 10px 10px;
					border-top: none;
				}

				.labarugi-unit-grid-scroll {
					display: block;
					width: 100%;
					max-width: 100%;
					height: auto;
					max-height: none;
					overflow-x: auto;
					overflow-y: visible;
					-webkit-overflow-scrolling: touch;
					border-radius: 10px;
					border: 1px solid #dce8e0;
					background: #fff;
					position: relative;
				}

				.labarugi-unit-grid-scroll-hint {
					font-size: 0.72rem;
					color: #6c757d;
					text-align: right;
					margin: 0 0 6px;
					font-style: italic;
				}

				.labarugi-unit-grid-table {
					width: max-content;
					min-width: 100%;
					border-collapse: separate;
					border-spacing: 0;
					margin: 0;
				}

				.labarugi-unit-grid-table thead th {
					background: linear-gradient(180deg, #1a5f3f 0%, #2e8b57 100%);
					color: #f5fff8;
					font-size: 0.78rem;
					font-weight: 700;
					padding: 10px 8px;
					text-align: center;
					border-bottom: 2px solid #39ff88;
					vertical-align: bottom;
					white-space: nowrap;
					position: relative;
				}

				.labarugi-unit-grid-table thead th.labarugi-grid-col-ket.sticky-col {
					position: sticky;
					left: 0;
					z-index: 5;
					min-width: 320px;
					max-width: 380px;
					width: 320px;
					text-align: left;
					background: linear-gradient(180deg, #1a5f3f 0%, #2e8b57 100%);
					box-shadow: 4px 0 8px -2px rgba(0, 0, 0, 0.18);
				}

				.labarugi-grid-unit-kode {
					display: block;
					font-size: 0.72rem;
					opacity: 0.9;
					letter-spacing: 0.03em;
				}

				.labarugi-grid-unit-nama {
					display: block;
					font-size: 0.68rem;
					font-weight: 500;
					opacity: 0.95;
					max-width: 110px;
					overflow: hidden;
					text-overflow: ellipsis;
				}

				.labarugi-grid-unit-head {
					display: flex;
					align-items: flex-start;
					justify-content: space-between;
					gap: 6px;
					width: 100%;
				}

				.labarugi-grid-unit-head-text {
					flex: 1 1 auto;
					min-width: 0;
					text-align: left;
				}

				.labarugi-unit-publish-check {
					flex: 0 0 auto;
					display: flex;
					flex-direction: column;
					align-items: center;
					justify-content: center;
					gap: 2px;
					margin: 0;
					padding: 4px 5px;
					border-radius: 6px;
					background: rgba(255, 255, 255, 0.14);
					border: 1px solid rgba(255, 255, 255, 0.35);
					cursor: pointer;
					user-select: none;
				}

				.labarugi-unit-publish-check input[type="checkbox"] {
					width: 16px;
					height: 16px;
					margin: 0;
					cursor: pointer;
				}

				.labarugi-unit-publish-label {
					font-size: 0.58rem;
					font-weight: 700;
					line-height: 1;
					color: #fff;
					text-transform: uppercase;
					letter-spacing: 0.04em;
				}

				.labarugi-grid-col-unit.labarugi-unit-col-published {
					background: linear-gradient(180deg, #1b5e20 0%, #2e7d32 100%) !important;
					color: #fff;
				}

				.labarugi-grid-col-unit.labarugi-unit-col-unpublished {
					background: linear-gradient(180deg, #b71c1c 0%, #c62828 100%) !important;
					color: #fff;
				}

				.labarugi-grid-cell.labarugi-unit-col-published {
					background: #c8e6c9 !important;
				}

				.labarugi-grid-cell.labarugi-unit-col-unpublished {
					background: #ffcdd2 !important;
				}

				.labarugi-unit-grid-table tbody tr:nth-child(even) td.labarugi-grid-cell.labarugi-unit-col-published {
					background: #a5d6a7 !important;
				}

				.labarugi-unit-grid-table tbody tr:nth-child(even) td.labarugi-grid-cell.labarugi-unit-col-unpublished {
					background: #ef9a9a !important;
				}

				.labarugi-unit-grid-table tbody tr:hover td.labarugi-grid-cell.labarugi-unit-col-published {
					background: #b9dfbb !important;
				}

				.labarugi-unit-grid-table tbody tr:hover td.labarugi-grid-cell.labarugi-unit-col-unpublished {
					background: #e57373 !important;
				}

				.labarugi-grid-cell.labarugi-unit-col-published.labarugi-grid-cell-mismatch {
					background: #fff176 !important;
				}

				.labarugi-unit-grid-table tbody tr:nth-child(even) td.labarugi-grid-cell.labarugi-unit-col-published.labarugi-grid-cell-mismatch {
					background: #ffee58 !important;
				}

				.labarugi-unit-grid-table tbody tr:hover td.labarugi-grid-cell.labarugi-unit-col-published.labarugi-grid-cell-mismatch {
					background: #ffeb3b !important;
				}

				.labarugi-grid-cell.labarugi-grid-cell-match {
					background: linear-gradient(135deg, #b9f6ca 0%, #69f0ae 55%, #00e676 100%) !important;
					box-shadow: inset 0 0 0 1px rgba(0, 200, 83, 0.45);
				}

				.labarugi-unit-grid-table tbody tr:nth-child(even) td.labarugi-grid-cell.labarugi-grid-cell-match {
					background: linear-gradient(135deg, #a7ffcb 0%, #64ffda 50%, #1de9b6 100%) !important;
				}

				.labarugi-unit-grid-table tbody tr:hover td.labarugi-grid-cell.labarugi-grid-cell-match {
					background: linear-gradient(135deg, #ccff90 0%, #76ff03 45%, #00e676 100%) !important;
				}

				.labarugi-grid-cell.labarugi-grid-cell-match.labarugi-grid-cell-mismatch {
					background: linear-gradient(135deg, #b9f6ca 0%, #69f0ae 55%, #00e676 100%) !important;
				}

				.labarugi-grid-cell.labarugi-unit-col-published .labarugi-grid-input,
				.labarugi-grid-cell.labarugi-unit-col-unpublished .labarugi-grid-input {
					background: #fff !important;
					border-color: #90a4ae !important;
				}

				.labarugi-unit-grid-table tbody td {
					border-bottom: 1px solid #e8f0ea;
					padding: 8px 6px;
					vertical-align: middle;
					background: #fff;
				}

				.labarugi-unit-grid-table tbody tr:nth-child(even) td {
					background: #f9fcfa;
				}

				.labarugi-unit-grid-table tbody tr:hover td {
					background: #eef8f1;
				}

				.labarugi-grid-col-ket.sticky-col {
					position: sticky;
					left: 0;
					z-index: 4;
					min-width: 320px;
					max-width: 380px;
					width: 320px;
					background: #f7fbf8 !important;
					border-right: 2px solid rgba(57, 255, 136, 0.45);
					font-size: 0.78rem;
					color: #1b4332;
					box-shadow: 4px 0 8px -2px rgba(0, 0, 0, 0.1);
				}

				.labarugi-ket-label-row {
					display: flex;
					align-items: flex-start;
					justify-content: space-between;
					gap: 8px;
					width: 100%;
				}

				.labarugi-ket-label-text {
					flex: 1 1 auto;
					line-height: 1.35;
					padding-top: 2px;
				}

				.labarugi-ket-title-text {
					font-size: 0.95rem;
					font-weight: 800;
					color: #1b4332;
					line-height: 1.35;
				}

				.labarugi-ket-label-indent {
					padding-left: 1.2em;
					font-weight: 600;
				}

				.labarugi-ket-label-deep-indent {
					padding-left: 2.4em;
					font-weight: 600;
				}

				.labarugi-ket-label-bok-sub,
				.labarugi-ket-label-sub-muted {
					padding-left: 2.4em;
					font-size: 0.82em;
					font-style: italic;
					font-weight: 400 !important;
					color: #2d4a38;
					line-height: 1.35;
				}

				.labarugi-grid-input-row .labarugi-ket-label-bok-sub,
				.labarugi-grid-input-row .labarugi-ket-label-sub-muted {
					font-size: 0.78rem;
				}

				.labarugi-grid-input.labarugi-input-sub-muted,
				.labarugi-calc-input.labarugi-input-sub-muted {
					font-style: italic !important;
					font-weight: 400 !important;
				}

				.labarugi-grid-calc-cell.labarugi-calc-tier-subtotal .labarugi-calc-input.labarugi-input-sub-muted {
					font-weight: 400 !important;
				}

				.labarugi-ket-title-row,
				.labarugi-grid-title-row td {
					background: #eef5f0 !important;
				}

				.labarugi-grid-title-cell {
					text-align: center;
					vertical-align: middle;
				}

				.labarugi-calc-input {
					background: #fff8f8 !important;
					color: #c62828 !important;
					font-weight: 700 !important;
					text-align: right !important;
				}

				/* Subtotal calc: Total Penjualan Bersih, Total HPP, Total Beban Op., Total Pendapatan/Beban Lain (1.3x) */
				.labarugi-calc-tier-subtotal.labarugi-calc-input,
				.labarugi-utama-input-group.labarugi-calc-tier-subtotal .labarugi-calc-input {
					font-size: calc(1.1vw * 1.3) !important;
					font-weight: 800 !important;
					min-height: calc(32px * 1.3) !important;
					padding: calc(6px * 1.3) calc(8px * 1.3) !important;
					max-width: calc(220px * 1.3) !important;
					width: 100% !important;
				}

				.labarugi-grid-calc-cell.labarugi-calc-tier-subtotal .labarugi-calc-input {
					font-size: calc(0.72rem * 1.3) !important;
					font-weight: 800 !important;
					min-width: calc(140px * 1.3) !important;
					min-height: calc(32px * 1.3) !important;
					padding: calc(6px * 1.3) calc(8px * 1.3) !important;
				}

				.labarugi-grid-calc-cell.labarugi-calc-tier-subtotal .labarugi-calc-input-group {
					min-width: calc(150px * 1.3);
				}

				/* Major calc: Laba Bruto, Laba Operasional, Laba Sebelum Pajak (1.6x) */
				.labarugi-calc-tier-major.labarugi-calc-input,
				.labarugi-utama-input-group.labarugi-calc-tier-major .labarugi-calc-input {
					font-size: calc(1.1vw * 1.6) !important;
					font-weight: 800 !important;
					min-height: calc(32px * 1.6) !important;
					padding: calc(6px * 1.6) calc(8px * 1.6) !important;
					max-width: calc(220px * 1.6) !important;
					width: 100% !important;
				}

				.labarugi-grid-calc-cell.labarugi-calc-tier-major .labarugi-calc-input {
					font-size: calc(0.72rem * 1.6) !important;
					font-weight: 800 !important;
					min-width: calc(140px * 1.6) !important;
					min-height: calc(32px * 1.6) !important;
					padding: calc(6px * 1.6) calc(8px * 1.6) !important;
				}

				.labarugi-grid-calc-cell.labarugi-calc-tier-major .labarugi-calc-input-group {
					min-width: calc(150px * 1.6);
				}

				.labarugi-calc-input-group {
					display: flex;
					flex-direction: column;
					gap: 4px;
					align-items: stretch;
				}

				.labarugi-ket-summary-row,
				.labarugi-grid-summary-row td.labarugi-grid-col-ket,
				.labarugi-utama-summary-row th {
					background: #eef5f0 !important;
				}

				.labarugi-utama-wrap .labarugi-ket-label-row {
					align-items: flex-start;
				}

				.labarugi-utama-label-main,
				.labarugi-utama-label-utama_main,
				.labarugi-utama-label-utama_title,
				.labarugi-utama-label-utama_section {
					display: block;
					text-align: left;
					padding-left: 0;
					margin: 0;
				}

				.labarugi-utama-label-indent,
				.labarugi-utama-label-utama_indent {
					display: block;
					text-align: left;
					padding-left: 5ch;
					margin: 0;
				}

				.labarugi-utama-label-bold {
					font-weight: 700 !important;
				}

				.labarugi-utama-title-row th {
					background: transparent !important;
					border: none !important;
				}

				.labarugi-utama-wrap .labarugi-calc-input,
				.labarugi-utama-wrap .labarugi-utama-input {
					color: #000 !important;
					background: #fff !important;
					font-weight: 700 !important;
				}

				.labarugi-utama-nominal-border-double .labarugi-grid-input,
				.labarugi-utama-nominal-border-double.labarugi-grid-input,
				.labarugi-utama-nominal-border-double .labarugi-calc-input,
				.labarugi-utama-nominal-border-double.labarugi-calc-input {
					border-top: 3px double #000 !important;
					border-bottom: 3px double #000 !important;
					border-left: 1px solid #ccc !important;
					border-right: 1px solid #ccc !important;
				}

				.labarugi-utama-nominal-border-single .labarugi-grid-input,
				.labarugi-utama-nominal-border-single.labarugi-grid-input,
				.labarugi-utama-nominal-border-single .labarugi-calc-input,
				.labarugi-utama-nominal-border-single.labarugi-calc-input {
					border-top: 1px solid #000 !important;
					border-bottom: 1px solid #000 !important;
					border-left: 1px solid #ccc !important;
					border-right: 1px solid #ccc !important;
				}

				.labarugi-utama-wrap tr th {
					vertical-align: middle;
				}

				.labarugi-grid-calc-cell .labarugi-calc-input-group {
					min-width: 150px;
				}

				.labarugi-btn-setting-kode-akun {
					flex: 0 0 auto;
					background: linear-gradient(135deg, #1565c0, #0d47a1) !important;
					border: 1px solid #ffd54f !important;
					color: #ffffff !important;
					font-weight: 700;
					font-size: 0.68rem;
					padding: 4px 7px;
					border-radius: 6px;
					white-space: nowrap;
					box-shadow: 0 0 8px rgba(255, 213, 79, 0.45);
					text-shadow: 0 0 6px rgba(255, 255, 255, 0.7);
				}

				.labarugi-btn-setting-kode-akun:hover {
					color: #ffd700 !important;
					text-shadow: 0 0 10px rgba(255, 215, 0, 0.9);
				}

				#modalLabarugiKodeAkun {
					z-index: 20050 !important;
				}

				#modalLabarugiKodeAkun .labarugi-ka-modal-dialog {
					max-width: 1100px !important;
					width: 94vw !important;
					margin: 1rem auto;
					z-index: 20051;
				}

				#modalLabarugiKodeAkun .modal-body {
					overflow: hidden !important;
				}

				.labarugi-ka-dt-scroll-wrap {
					width: 100%;
					height: 360px;
					overflow: auto;
					border: 1px solid #dce8e0;
					border-radius: 8px;
					background: #fff;
				}

				.labarugi-ka-loading {
					display: none;
					padding: 20px;
					text-align: center;
					font-weight: 600;
				}

				.labarugi-ka-tables-wrap {
					position: relative;
					min-height: 220px;
				}

				.labarugi-ka-loading-overlay {
					display: none;
					position: absolute;
					inset: 0;
					z-index: 12;
					background: rgba(255, 255, 255, 0.82);
					align-items: center;
					justify-content: center;
					font-weight: 600;
					color: #0d47a1;
				}

				.labarugi-ka-loading-overlay.is-active {
					display: flex;
				}

				.labarugi-kode-akun-modal .modal-header {
					background: linear-gradient(135deg, #0d47a1, #1565c0);
					color: #fff;
				}

				.labarugi-kode-akun-modal .modal-header .close {
					color: #fff;
					opacity: 0.9;
				}

				.labarugi-ka-section-title {
					font-weight: 700;
					margin-bottom: 8px;
					color: #1b4332;
					font-size: 0.92rem;
				}

				.labarugi-ka-dt-row {
					margin-left: 0;
					margin-right: 0;
				}

				.labarugi-ka-dt-col {
					padding-left: 10px;
					padding-right: 10px;
				}

				.labarugi-ka-table thead th {
					background: #f1f8f4;
					font-size: 0.82rem;
				}

				.labarugi-utama-field-cell.labarugi-grid-cell-match {
					background: #d4edda !important;
				}

				.labarugi-utama-field-cell.labarugi-grid-cell-mismatch {
					background: #fff3cd !important;
				}

				.labarugi-utama-input-group {
					display: flex;
					flex-direction: column;
					align-items: flex-end;
					gap: 4px;
				}

				.labarugi-utama-input-group .labarugi-grid-input {
					width: 100%;
					max-width: 220px;
				}

				.labarugi-grid-ns-nominal {
					display: block;
					flex: 1 1 auto;
					min-width: 0;
					text-align: right;
					color: #dc3545 !important;
					font-weight: 700;
					font-size: 0.72rem;
					line-height: 1.2;
					margin-bottom: 0;
					text-decoration: none !important;
					cursor: pointer;
				}

				.labarugi-grid-auto-row {
					display: flex;
					align-items: center;
					justify-content: space-between;
					gap: 6px;
					width: 100%;
					margin-bottom: 4px;
				}

				.labarugi-grid-sync-auto-check {
					display: inline-flex;
					align-items: center;
					justify-content: center;
					flex: 0 0 auto;
					margin: 0;
					padding: 2px 4px;
					border-radius: 6px;
					border: 1px solid #81c784;
					background: #f1f8e9;
					cursor: pointer;
				}

				.labarugi-grid-sync-auto-check:hover {
					background: #e8f5e9;
					border-color: #43a047;
				}

				.labarugi-grid-sync-auto-cb {
					margin: 0 4px 0 0;
					cursor: pointer;
					transform: scale(1.15);
				}

				.labarugi-grid-sync-auto-label {
					font-size: 0.72rem;
					color: #2e7d32;
					line-height: 1;
				}

				.labarugi-grid-sync-auto-check.is-loading {
					opacity: 0.65;
					pointer-events: none;
				}

				.labarugi-grid-ns-nominal:hover {
					color: #a71d2a !important;
					text-decoration: underline !important;
				}

				#modalLabarugiTransaksiUnit .labarugi-trx-modal-dialog {
					max-width: 1100px !important;
					width: 94vw !important;
					margin: 1rem auto;
				}

				#modalLabarugiTransaksiUnit .modal-body {
					overflow: hidden !important;
				}

				.labarugi-trx-dt-scroll-wrap {
					width: 100%;
					height: 400px;
					overflow: hidden;
					border: 1px solid #dce8e0;
					border-radius: 8px;
					background: #fff;
				}

				.labarugi-trx-loading {
					display: none;
					padding: 20px;
					text-align: center;
					font-weight: 600;
				}

				.labarugi-trx-modal .modal-header {
					background: linear-gradient(135deg, #b71c1c, #d32f2f);
					color: #fff;
				}

				.labarugi-trx-modal .modal-header .close {
					color: #fff;
					opacity: 0.9;
				}

				.labarugi-unit-grid-table tbody tr:nth-child(even) td.labarugi-grid-col-ket.sticky-col {
					background: #f2f9f5 !important;
				}

				.labarugi-unit-grid-table tbody tr:hover td.labarugi-grid-col-ket.sticky-col {
					background: #e8f5ec !important;
				}

				.labarugi-grid-col-unit {
					min-width: 180px;
					width: 180px;
				}

				.labarugi-grid-input-group {
					display: flex;
					flex-direction: column;
					gap: 4px;
					align-items: stretch;
				}

				.labarugi-grid-input {
					width: 100% !important;
					min-width: 140px;
					height: auto !important;
					min-height: 32px;
					padding: 6px 6px !important;
					margin: 0 !important;
					background: #fff !important;
					color: #1b4332 !important;
					border: 1px solid #c5d9cc !important;
					border-radius: 6px !important;
					font-size: 0.72rem !important;
					font-weight: 600;
					text-align: right;
					letter-spacing: -0.02em;
				}

				.labarugi-grid-input:focus {
					border-color: #39ff88 !important;
					box-shadow: 0 0 0 2px rgba(57, 255, 136, 0.25) !important;
					outline: none;
				}

				.labarugi-grid-btn-save {
					background: linear-gradient(135deg, #0d6efd, #0056d6);
					border: 1px solid #ffd54a;
					color: #ffe566;
					font-weight: 700;
					font-size: 0.72rem;
					padding: 4px 8px;
					border-radius: 6px;
					transition: all 0.2s ease;
				}

				.labarugi-grid-btn-save:not(:disabled):hover {
					background: linear-gradient(135deg, #1e88ff, #0d6efd);
					box-shadow: 0 4px 12px rgba(13, 110, 253, 0.35);
				}

				.labarugi-grid-btn-save:disabled {
					background: #e9ecef;
					border-color: #dee2e6;
					color: #adb5bd;
					cursor: not-allowed;
					opacity: 0.75;
				}

				.labarugi-grid-status {
					font-size: 0.68rem;
					min-height: 14px;
					text-align: center;
				}

				.labarugi-grid-status.is-ok { color: #198754; font-weight: 600; }
				.labarugi-grid-status.is-error { color: #dc3545; font-weight: 600; }
				.labarugi-grid-status.is-loading { color: #0d6efd; font-style: italic; }
			</style>


			<style>
				input[type=text] {
					width: 90%;
					padding: 12px 16px;
					height: 12px;
					margin: 4px 0;
					box-sizing: border-box;
					border: none;
					background-color: #3CBC8D;
					color: white;
				}
			</style>

		</head>

		<!-- <body> -->







		<?php
		$labarugi_tab_storage_key = 'labarugi_form_active_tab_' . (int) $tahun_neraca . '_' . (int) $bulan_transaksi;
		$labarugi_partial = APPPATH . 'views/anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_form_panel.php';
		?>
		<!-- labarugi-form-deploy: v2-per-unit-tabs -->

		<div class="labarugi-tabs-shell" id="labarugiTabsShell" data-storage-key="<?php echo htmlspecialchars($labarugi_tab_storage_key, ENT_QUOTES, 'UTF-8'); ?>">
			<ul class="labarugi-tab-nav" role="tablist">
				<li><button type="button" class="labarugi-tab-btn is-active" data-tab="utama" role="tab" aria-selected="true">Laba Rugi</button></li>
				<li><button type="button" class="labarugi-tab-btn" data-tab="rinci" role="tab" aria-selected="false">Laba Rugi Per Unit (RINCI)</button></li>
				<li><button type="button" class="labarugi-tab-btn" data-tab="sederhana" role="tab" aria-selected="false">Laba Rugi Per Unit (Sederhana)</button></li>
			</ul>

			<div class="labarugi-tab-panels">
				<div class="labarugi-tab-panel is-active" id="labarugi-tab-utama" data-tab-panel="utama" role="tabpanel">
					<?php
					$labarugi_view_mode = 'utama';
					$labarugi_tab_key = 'utama';
					include $labarugi_partial;
					?>
				</div>
				<div class="labarugi-tab-panel" id="labarugi-tab-rinci" data-tab-panel="rinci" role="tabpanel">
					<?php
					$labarugi_view_mode = 'rinci';
					$labarugi_tab_key = 'rinci';
					include $labarugi_partial;
					?>
				</div>
				<div class="labarugi-tab-panel" id="labarugi-tab-sederhana" data-tab-panel="sederhana" role="tabpanel">
					<?php
					$labarugi_view_mode = 'sederhana';
					$labarugi_tab_key = 'sederhana';
					include $labarugi_partial;
					?>
				</div>
			</div>
		</div>

		<?php $this->load->view('anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_keterangan_modal'); ?>
		<?php $this->load->view('anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_kode_akun_modal'); ?>
		<?php $this->load->view('anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_transaksi_modal'); ?>
		<?php $this->load->view('anekadharma/tbl_laba_rugi/partials/adminlte310_labarugi_kode_akun_script'); ?>

		<script>
		(function() {
			var shell = document.getElementById('labarugiTabsShell');
			if (!shell) { return; }
			var storageKey = shell.getAttribute('data-storage-key') || 'labarugi_form_active_tab';
			var buttons = shell.querySelectorAll('.labarugi-tab-btn');
			var panels = shell.querySelectorAll('.labarugi-tab-panel');

			function activateTab(tabId, persist) {
				if (!tabId) { return; }
				var found = false;
				buttons.forEach(function(btn) {
					var active = btn.getAttribute('data-tab') === tabId;
					if (active) { found = true; }
					btn.classList.toggle('is-active', active);
					btn.setAttribute('aria-selected', active ? 'true' : 'false');
				});
				panels.forEach(function(panel) {
					panel.classList.toggle('is-active', panel.getAttribute('data-tab-panel') === tabId);
				});
				if (found && persist !== false) {
					try { localStorage.setItem(storageKey, tabId); } catch (e) {}
				}
				if (typeof window.labarugiRefreshDualScroll === 'function') {
					setTimeout(window.labarugiRefreshDualScroll, 60);
				}
				if (typeof window.labarugiInitAutoCalc === 'function') {
					setTimeout(window.labarugiInitAutoCalc, 120);
				}
			}

			buttons.forEach(function(btn) {
				btn.addEventListener('click', function() {
					activateTab(btn.getAttribute('data-tab'), true);
				});
			});

			var savedTab = null;
			try { savedTab = localStorage.getItem(storageKey); } catch (e) {}
			if (savedTab && shell.querySelector('[data-tab-panel="' + savedTab + '"]')) {
				activateTab(savedTab, false);
			}
		})();
		</script>

		<script>
		(function() {
			<?php
			$this->load->helper(array('laba_rugi_keterangan', 'laba_rugi_unit_tab_sync'));
			?>
			var LABARUGI_CALC_DEFS_BY_TAB = <?php echo json_encode(array(
				'utama' => labarugi_keterangan_calc_definitions_for_tab('utama'),
				'rinci' => labarugi_keterangan_calc_definitions_for_tab('rinci'),
				'sederhana' => labarugi_keterangan_calc_definitions_for_tab('sederhana'),
			)); ?>;
			var LABARUGI_CALC_ORDER_BY_TAB = <?php echo json_encode(array(
				'utama' => labarugi_keterangan_calc_order_for_tab('utama'),
				'rinci' => labarugi_keterangan_calc_order_for_tab('rinci'),
				'sederhana' => labarugi_keterangan_calc_order_for_tab('sederhana'),
			)); ?>;
			var LABARUGI_UNIT_TAB_SYNC = <?php echo json_encode(labarugi_unit_tab_sync_js_config()); ?>;

			function labarugiGetJenisTabFromScope(scope) {
				if (!scope) { return 'utama'; }
				return scope.getAttribute('data-jenis-tab') || 'utama';
			}

			function labarugiGetCalcDefs(scope) {
				var tab = labarugiGetJenisTabFromScope(scope);
				return LABARUGI_CALC_DEFS_BY_TAB[tab] || LABARUGI_CALC_DEFS_BY_TAB['utama'];
			}

			function labarugiGetCalcOrder(scope) {
				var tab = labarugiGetJenisTabFromScope(scope);
				return LABARUGI_CALC_ORDER_BY_TAB[tab] || LABARUGI_CALC_ORDER_BY_TAB['utama'];
			}

			function labarugiUnitTabSyncPeerTab(jenisTab) {
				return jenisTab === 'rinci' ? 'sederhana' : (jenisTab === 'sederhana' ? 'rinci' : '');
			}

			function labarugiUnitTabSyncIsDerivedKey(ketKey) {
				return (LABARUGI_UNIT_TAB_SYNC.rinciDerivedKeys || []).indexOf(ketKey) >= 0;
			}

			function labarugiUnitTabSyncIsRinciOnlyKey(ketKey) {
				return (LABARUGI_UNIT_TAB_SYNC.rinciOnlyKeys || []).indexOf(ketKey) >= 0;
			}

			function labarugiUnitTabSyncShouldMirror(ketKey, sourceTab) {
				if (sourceTab !== 'rinci' && sourceTab !== 'sederhana') { return false; }
				if (labarugiUnitTabSyncIsRinciOnlyKey(ketKey)) { return false; }
				if (sourceTab === 'sederhana' && labarugiUnitTabSyncIsDerivedKey(ketKey)) { return false; }
				return true;
			}

			function labarugiUnitTabSyncFindPeerGroup(ketKey, unitKey, sourceTab) {
				var peerTab = labarugiUnitTabSyncPeerTab(sourceTab);
				if (!peerTab) { return null; }
				var peerWrap = document.querySelector('.labarugi-unit-grid-wrap[data-jenis-tab="' + peerTab + '"]');
				if (!peerWrap) { return null; }
				var selector = '.labarugi-grid-input-group[data-nama-laba-rugi="' + labarugiEscSelector(ketKey) + '"][data-unit="' + labarugiEscSelector(unitKey) + '"]';
				return peerWrap.querySelector(selector);
			}

			function labarugiUnitTabSyncApplyPeerValue(peerGroup, formattedValue, options) {
				if (!peerGroup) { return; }
				options = options || {};
				var input = peerGroup.querySelector('.labarugi-grid-input');
				if (!input) { return; }
				if (typeof formattedValue === 'string' && formattedValue !== '') {
					input.value = formattedValue;
				}
				if (peerGroup.getAttribute('data-rinci-derived') === '1') {
					labarugiGridRefreshMatch(peerGroup);
					return;
				}
				var btn = peerGroup.querySelector('.labarugi-grid-btn-save');
				if (btn && options.fromServerSave) {
					btn.disabled = true;
				} else if (typeof labarugiGridToggleSave === 'function') {
					labarugiGridToggleSave(peerGroup);
				}
				labarugiGridRefreshMatch(peerGroup);
				if (!options.skipRecalc) {
					labarugiTriggerRecalcFromGroup(peerGroup);
				}
			}

			function labarugiUnitTabSyncApplyDerivedPeerValues(unitKey, derivedValues) {
				if (!unitKey || !derivedValues) { return; }
				var sederhanaWrap = document.querySelector('.labarugi-unit-grid-wrap[data-jenis-tab="sederhana"]');
				if (!sederhanaWrap) { return; }
				Object.keys(derivedValues).forEach(function(ketKey) {
					var peerGroup = sederhanaWrap.querySelector(
						'.labarugi-grid-input-group[data-nama-laba-rugi="' + labarugiEscSelector(ketKey) + '"][data-unit="' + labarugiEscSelector(unitKey) + '"]'
					);
					labarugiUnitTabSyncApplyPeerValue(peerGroup, derivedValues[ketKey], { skipRecalc: false });
				});
				if (typeof labarugiRecalcUnitGrid === 'function') {
					labarugiRecalcUnitGrid(sederhanaWrap);
				}
			}

			function labarugiUnitTabSyncAfterSave(group, formattedValue) {
				if (!group) { return; }
				var jenisTab = group.getAttribute('data-jenis-tab');
				var ketKey = group.getAttribute('data-nama-laba-rugi');
				var unitKey = group.getAttribute('data-unit');
				if (!labarugiUnitTabSyncShouldMirror(ketKey, jenisTab)) { return; }
				var peerGroup = labarugiUnitTabSyncFindPeerGroup(ketKey, unitKey, jenisTab);
				labarugiUnitTabSyncApplyPeerValue(peerGroup, formattedValue, { fromServerSave: true });
			}

			function labarugiUnitTabSyncRinciDerivedToSederhana(rinciWrap, values, unitKey) {
				if (!rinciWrap || rinciWrap.getAttribute('data-jenis-tab') !== 'rinci') { return; }
				var sederhanaWrap = document.querySelector('.labarugi-unit-grid-wrap[data-jenis-tab="sederhana"]');
				if (!sederhanaWrap || !values) { return; }
				(LABARUGI_UNIT_TAB_SYNC.rinciDerivedKeys || []).forEach(function(ketKey) {
					if (typeof values[ketKey] === 'undefined') { return; }
					var selector = '.labarugi-grid-input-group[data-nama-laba-rugi="' + labarugiEscSelector(ketKey) + '"]';
					if (unitKey) {
						selector += '[data-unit="' + labarugiEscSelector(unitKey) + '"]';
					}
					sederhanaWrap.querySelectorAll(selector).forEach(function(peerGroup) {
						labarugiUnitTabSyncApplyPeerValue(peerGroup, labarugiGridFormatNominal(values[ketKey]), { skipRecalc: false });
					});
				});
				if (typeof labarugiRecalcUnitGrid === 'function') {
					labarugiRecalcUnitGrid(sederhanaWrap);
				}
			}

			function labarugiGridParseNominal(val) {
				var str = String(val || '').trim();
				if (str === '') { return 0; }
				str = str.replace(/\./g, '').replace(',', '.');
				var num = parseFloat(str);
				return isNaN(num) ? 0 : num;
			}

			function labarugiGridRefreshMatch(group) {
				if (!group) { return; }
				var cell = group.closest('.labarugi-grid-cell') || group.closest('.labarugi-utama-field-cell');
				var nominalBtn = group.querySelector('.labarugi-grid-ns-nominal');
				var input = group.querySelector('.labarugi-grid-input');
				if (!cell || !nominalBtn || !input) { return; }
				var nsVal = parseFloat(nominalBtn.getAttribute('data-nominal') || '0');
				if (isNaN(nsVal)) {
					nsVal = labarugiGridParseNominal(nominalBtn.textContent);
				}
				var inVal = labarugiGridParseNominal(input.value);
				var isMatch = Math.abs(nsVal - inVal) < 0.01;
				cell.classList.toggle('labarugi-grid-cell-match', isMatch);
				if (isMatch) {
					cell.classList.remove('labarugi-grid-cell-mismatch');
				} else if (cell.classList.contains('labarugi-unit-col-published') || cell.classList.contains('labarugi-utama-field-cell')) {
					cell.classList.add('labarugi-grid-cell-mismatch');
				} else {
					cell.classList.remove('labarugi-grid-cell-mismatch');
				}
			}

			function labarugiGridFormatNominal(num) {
				var n = parseFloat(num);
				if (isNaN(n)) { n = 0; }
				var parts = n.toFixed(2).split('.');
				parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
				return parts[0] + ',' + parts[1];
			}

			function labarugiGetGroupValue(group) {
				var input = group.querySelector('.labarugi-grid-input');
				if (!input) { return 0; }
				return labarugiGridParseNominal(input.value);
			}

			function labarugiCalcValue(def, values) {
				if (!def || !def.parts) { return 0; }
				if (def.type === 'sum') {
					var total = 0;
					def.parts.forEach(function(key) {
						total += values[key] || 0;
					});
					return total;
				}
				if (def.type === 'subtract') {
					return (values[def.parts[0]] || 0) - (values[def.parts[1]] || 0);
				}
				if (def.type === 'add_sub') {
					return (values[def.parts[0]] || 0) + (values[def.parts[1]] || 0) - (values[def.parts[2]] || 0);
				}
				return 0;
			}

			function labarugiApplyCalcToGroup(group, result) {
				if (!group || group.getAttribute('data-is-calculated') !== '1') { return; }
				var input = group.querySelector('.labarugi-grid-input');
				if (!input) { return; }
				input.value = labarugiGridFormatNominal(result);
				var btn = group.querySelector('.labarugi-grid-btn-save');
				if (btn) {
					btn.disabled = false;
				}
				if (typeof labarugiGridToggleSave === 'function') {
					labarugiGridToggleSave(group);
				}
			}

			function labarugiCollectSourceValuesForUnit(table, unitSel) {
				var values = {};
				if (!table) { return values; }
				table.querySelectorAll('.labarugi-grid-cell' + unitSel + ' .labarugi-grid-input-group[data-nama-laba-rugi]').forEach(function(group) {
					if (group.getAttribute('data-is-calculated') === '1') { return; }
					var key = group.getAttribute('data-nama-laba-rugi');
					if (key) {
						values[key] = labarugiGetGroupValue(group);
					}
				});
				return values;
			}

			function labarugiApplyCalcChainForUnit(table, unitSel, values) {
				if (!table || !values) { return values; }
				var wrap = table.closest('.labarugi-unit-grid-wrap');
				var calcOrder = labarugiGetCalcOrder(wrap || table);
				var calcDefs = labarugiGetCalcDefs(wrap || table);
				calcOrder.forEach(function(calcKey) {
					var def = calcDefs[calcKey];
					if (!def) { return; }
					var result = labarugiCalcValue(def, values);
					values[calcKey] = result;
					var groupSel = '.labarugi-grid-cell' + unitSel + ' .labarugi-grid-input-group[data-nama-laba-rugi="' + labarugiEscSelector(calcKey) + '"]';
					labarugiApplyCalcToGroup(table.querySelector(groupSel), result);
				});
				if (wrap && wrap.getAttribute('data-jenis-tab') === 'rinci') {
					var unitKey = '';
					var unitMatch = unitSel.match(/data-unit-key="((?:\\.|[^"\\])*)"/);
					if (unitMatch) {
						unitKey = unitMatch[1].replace(/\\"/g, '"').replace(/\\\\/g, '\\');
					}
					labarugiUnitTabSyncRinciDerivedToSederhana(wrap, values, unitKey);
				}
				return values;
			}

            function labarugiEscSelector(val) {
                var str = String(val || '');
                if (window.CSS && typeof CSS.escape === 'function') {
                    return CSS.escape(str);
                }
                return str.replace(/\\/g, '\\\\').replace(/"/g, '\\"');
            }

            function labarugiCollectSourceValues(scope) {
				var values = {};
				if (!scope) { return values; }
				scope.querySelectorAll('.labarugi-grid-input-group[data-nama-laba-rugi]').forEach(function(group) {
					if (group.getAttribute('data-is-calculated') === '1') { return; }
					var key = group.getAttribute('data-nama-laba-rugi');
					if (key) {
						values[key] = labarugiGetGroupValue(group);
					}
				});
				return values;
			}

			function labarugiApplyCalcChain(scope, values) {
				if (!scope || !values) { return values; }
				var calcOrder = labarugiGetCalcOrder(scope);
				var calcDefs = labarugiGetCalcDefs(scope);
				calcOrder.forEach(function(calcKey) {
					var def = calcDefs[calcKey];
					if (!def) { return; }
					var result = labarugiCalcValue(def, values);
					values[calcKey] = result;
					scope.querySelectorAll('.labarugi-grid-input-group[data-nama-laba-rugi="' + labarugiEscSelector(calcKey) + '"]').forEach(function(group) {
						labarugiApplyCalcToGroup(group, result);
					});
				});
				return values;
			}

			function labarugiFindUtamaScope(el) {
				if (!el || !el.closest) { return null; }
				var wrap = el.closest('.labarugi-utama-wrap');
				if (wrap && wrap.querySelector('.labarugi-grid-input-group[data-nama-laba-rugi]')) {
					return wrap;
				}
				var table = el.closest('table.customers-labarugi-table');
				if (table && table.querySelector('.labarugi-utama-input-group, .labarugi-utama-field-cell, .labarugi-calc-input-group')) {
					return table;
				}
				return wrap;
			}

			function labarugiRecalcUtama(scope) {
				if (!scope) { return; }
				var values = labarugiCollectSourceValues(scope);
				labarugiApplyCalcChain(scope, values);
			}

			function labarugiRecalcUnitGrid(wrap) {
				if (!wrap) { return; }
				var table = wrap.querySelector('.labarugi-unit-grid-table');
				if (!table) { return; }
				var unitKeys = [];
				table.querySelectorAll('thead .labarugi-grid-col-unit[data-unit-key]').forEach(function(th) {
					unitKeys.push(th.getAttribute('data-unit-key'));
				});
				unitKeys.forEach(function(unitKey) {
					var unitSel = '[data-unit-key="' + labarugiEscSelector(unitKey) + '"]';
					var values = labarugiCollectSourceValuesForUnit(table, unitSel);
					labarugiApplyCalcChainForUnit(table, unitSel, values);
				});
			}

			function labarugiTriggerRecalcFromGroup(group) {
				if (!group) { return; }
				var utamaScope = labarugiFindUtamaScope(group);
				var gridWrap = group.closest('.labarugi-unit-grid-wrap');
				if (utamaScope) {
					labarugiRecalcUtama(utamaScope);
				} else if (gridWrap) {
					labarugiRecalcUnitGrid(gridWrap);
				}
			}

			function labarugiInitAutoCalc() {
				document.querySelectorAll('.labarugi-utama-wrap').forEach(function(wrap) {
					labarugiRecalcUtama(wrap);
				});
				document.querySelectorAll('table.customers-labarugi-table').forEach(function(table) {
					if (table.querySelector('.labarugi-utama-input-group, .labarugi-calc-input-group')) {
						labarugiRecalcUtama(table);
					}
				});
				document.querySelectorAll('.labarugi-unit-grid-wrap').forEach(function(wrap) {
					labarugiRecalcUnitGrid(wrap);
				});
			}

			function labarugiGridGetAutoNominal(group) {
				var nominalBtn = group.querySelector('.labarugi-grid-ns-nominal');
				if (!nominalBtn) { return 0; }
				var nsVal = parseFloat(nominalBtn.getAttribute('data-nominal') || '0');
				if (isNaN(nsVal)) {
					nsVal = labarugiGridParseNominal(nominalBtn.textContent);
				}
				return nsVal;
			}

			function labarugiGridApplyAutoToInput(group, formattedValue) {
				var input = group.querySelector('.labarugi-grid-input');
				if (!input) { return; }
				if (typeof formattedValue === 'string' && formattedValue !== '') {
					input.value = formattedValue;
				} else {
					input.value = labarugiGridFormatNominal(labarugiGridGetAutoNominal(group));
				}
				labarugiGridToggleSave(group);
				labarugiGridRefreshMatch(group);
				labarugiTriggerRecalcFromGroup(group);
			}

			function labarugiGridSaveSyncAuto(group, isChecked, syncCb, syncLabel) {
				var syncUrl = group.getAttribute('data-sync-auto-url');
				if (!syncUrl) { return Promise.resolve(false); }

				var autoNominal = labarugiGridGetAutoNominal(group);
				var formData = new FormData();
				formData.append('tahun', group.getAttribute('data-tahun'));
				formData.append('bulan', group.getAttribute('data-bulan'));
				formData.append('jenis_tab', group.getAttribute('data-jenis-tab'));
				formData.append('nama_laba_rugi', group.getAttribute('data-nama-laba-rugi'));
				formData.append('unit', group.getAttribute('data-unit'));
				formData.append('keterangan_data', group.getAttribute('data-nama-label'));
				formData.append('status_sync_auto', isChecked ? '1' : '0');
				formData.append('auto_sistem', String(autoNominal));
				if (group.getAttribute('data-jenis-tab') === 'utama') {
					formData.append('field_key', group.getAttribute('data-field-key') || group.getAttribute('data-nama-laba-rugi'));
					formData.append('record_id', group.getAttribute('data-record-id') || '0');
				}

				if (syncLabel) { syncLabel.classList.add('is-loading'); }
				if (syncCb) { syncCb.disabled = true; }

				return fetch(syncUrl, {
					method: 'POST',
					body: formData,
					credentials: 'same-origin'
				})
				.then(function(res) { return res.json(); })
				.then(function(data) {
					if (syncLabel) { syncLabel.classList.remove('is-loading'); }
					if (syncCb) { syncCb.disabled = false; }
					if (data && data.ok) {
						group.setAttribute('data-sync-auto', isChecked ? '1' : '0');
						if (data.uuid_laba_rugi) {
							group.setAttribute('data-uuid', data.uuid_laba_rugi);
						}
						if (isChecked && data.nominal_formatted) {
							labarugiGridApplyAutoToInput(group, data.nominal_formatted);
						} else {
							labarugiGridRefreshMatch(group);
						}
						if (data.peer_synced) {
							var syncInput = group.querySelector('.labarugi-grid-input');
							labarugiUnitTabSyncAfterSave(group, data.nominal_formatted || (syncInput ? syncInput.value : ''));
						}
						return true;
					}
					if (syncCb) { syncCb.checked = !isChecked; }
					group.setAttribute('data-sync-auto', syncCb && syncCb.checked ? '1' : '0');
					if (typeof Swal !== 'undefined') {
						Swal.fire({ icon: 'error', title: 'Gagal', text: (data && data.message) ? data.message : 'Gagal menyimpan status sync otomatis.' });
					}
					return false;
				})
				.catch(function() {
					if (syncLabel) { syncLabel.classList.remove('is-loading'); }
					if (syncCb) {
						syncCb.disabled = false;
						syncCb.checked = !isChecked;
					}
					group.setAttribute('data-sync-auto', syncCb && syncCb.checked ? '1' : '0');
					if (typeof Swal !== 'undefined') {
						Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan koneksi.' });
					}
					return false;
				});
			}

			function labarugiApplyUnitColumnState(table, unitKey, isPublished) {
				if (!table || !unitKey) { return; }
				var pubClass = 'labarugi-unit-col-published';
				var unpubClass = 'labarugi-unit-col-unpublished';
				var sel = '[data-unit-key="' + labarugiEscSelector(unitKey) + '"]';
				table.querySelectorAll(sel).forEach(function(el) {
					el.classList.toggle(pubClass, isPublished);
					el.classList.toggle(unpubClass, !isPublished);
				});
				table.querySelectorAll('.labarugi-grid-cell' + sel + ' .labarugi-grid-input-group').forEach(function(group) {
					labarugiGridRefreshMatch(group);
				});
			}

			function labarugiInitUnitPublish() {
				document.querySelectorAll('.labarugi-unit-grid-wrap').forEach(function(wrap) {
					var table = wrap.querySelector('.labarugi-unit-grid-table');
					if (!table) { return; }

					wrap.querySelectorAll('.labarugi-unit-publish-cb').forEach(function(cb) {
						var unitKey = cb.getAttribute('data-unit') || '';
						labarugiApplyUnitColumnState(table, unitKey, cb.checked);

						cb.addEventListener('change', function() {
							var isPublished = cb.checked;
							labarugiApplyUnitColumnState(table, unitKey, isPublished);

							var publishUrl = wrap.getAttribute('data-publish-url');
							if (!publishUrl) { return; }

							var formData = new FormData();
							formData.append('tahun', wrap.getAttribute('data-tahun') || '');
							formData.append('bulan', wrap.getAttribute('data-bulan') || '');
							formData.append('jenis_tab', wrap.getAttribute('data-jenis-tab') || '');
							formData.append('unit', unitKey);
							formData.append('status_publish_unit', isPublished ? '1' : '0');
							formData.append('is_publish', isPublished ? '1' : '0');

							cb.disabled = true;
							fetch(publishUrl, {
								method: 'POST',
								body: formData,
								credentials: 'same-origin'
							})
							.then(function(res) { return res.json(); })
							.then(function(data) {
								cb.disabled = false;
								if (!data || !data.ok) {
									cb.checked = !isPublished;
									labarugiApplyUnitColumnState(table, unitKey, cb.checked);
									if (typeof Swal !== 'undefined') {
										Swal.fire({ icon: 'error', title: 'Gagal', text: (data && data.message) ? data.message : 'Gagal menyimpan status publish unit.' });
									}
								}
							})
							.catch(function() {
								cb.disabled = false;
								cb.checked = !isPublished;
								labarugiApplyUnitColumnState(table, unitKey, cb.checked);
								if (typeof Swal !== 'undefined') {
									Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan koneksi.' });
								}
							});
						});
					});
				});
			}

			window.labarugiRefreshGridMatch = labarugiGridRefreshMatch;

			function labarugiGridHasValue(val) {
				return String(val || '').replace(/\s/g, '') !== '';
			}

			function labarugiGridToggleSave(group) {
				var input = group.querySelector('.labarugi-grid-input');
				var btn = group.querySelector('.labarugi-grid-btn-save');
				if (!input || !btn) { return; }
				btn.disabled = !labarugiGridHasValue(input.value);
			}

			document.querySelectorAll('.labarugi-grid-input-group').forEach(function(group) {
				var input = group.querySelector('.labarugi-grid-input');
				var btn = group.querySelector('.labarugi-grid-btn-save');
				var status = group.querySelector('.labarugi-grid-status');
				if (!input || !btn) { return; }

				labarugiGridToggleSave(group);
				labarugiGridRefreshMatch(group);

				input.addEventListener('input', onSourceInputChange);
				input.addEventListener('keyup', onSourceInputChange);
				input.addEventListener('change', onSourceInputChange);

				function onSourceInputChange() {
					if (group.getAttribute('data-is-calculated') === '1') { return; }
					labarugiGridToggleSave(group);
					labarugiGridRefreshMatch(group);
					labarugiTriggerRecalcFromGroup(group);
					if (status) {
						status.textContent = '';
						status.className = 'labarugi-grid-status';
					}
				}

				var syncCb = group.querySelector('.labarugi-grid-sync-auto-cb');
				var syncLabel = group.querySelector('.labarugi-grid-sync-auto-check');
				if (syncCb) {
					syncCb.addEventListener('change', function() {
						var isChecked = syncCb.checked;
						if (isChecked) {
							labarugiGridApplyAutoToInput(group);
						}
						labarugiGridSaveSyncAuto(group, isChecked, syncCb, syncLabel);
					});
				}

				if (group.getAttribute('data-sync-auto') === '1') {
					labarugiGridApplyAutoToInput(group);
				}

				btn.addEventListener('click', function() {
					if (btn.disabled) { return; }

					var saveUrl = group.getAttribute('data-save-url');
					var formData = new FormData();
					formData.append('tahun', group.getAttribute('data-tahun'));
					formData.append('bulan', group.getAttribute('data-bulan'));
					formData.append('jenis_tab', group.getAttribute('data-jenis-tab'));
					formData.append('nama_laba_rugi', group.getAttribute('data-nama-laba-rugi'));
					formData.append('unit', group.getAttribute('data-unit'));
					formData.append('nominal', input.value);
					formData.append('keterangan_data', group.getAttribute('data-nama-label'));
					if (group.getAttribute('data-jenis-tab') === 'utama') {
						formData.append('field_key', group.getAttribute('data-field-key') || group.getAttribute('data-nama-laba-rugi'));
						formData.append('record_id', group.getAttribute('data-record-id') || '0');
					}

					btn.disabled = true;
					if (status) {
						status.textContent = 'Menyimpan...';
						status.className = 'labarugi-grid-status is-loading';
					}

					fetch(saveUrl, {
						method: 'POST',
						body: formData,
						credentials: 'same-origin'
					})
					.then(function(res) { return res.json(); })
					.then(function(data) {
						if (data && data.ok) {
							if (data.nominal_formatted) {
								input.value = data.nominal_formatted;
							}
							if (data.uuid_laba_rugi) {
								group.setAttribute('data-uuid', data.uuid_laba_rugi);
							}
							if (data.record_id) {
								group.setAttribute('data-record-id', data.record_id);
							}
							if (status) {
								status.textContent = 'Tersimpan';
								status.className = 'labarugi-grid-status is-ok';
							}
							labarugiGridToggleSave(group);
							labarugiGridRefreshMatch(group);
							if (data.peer_synced) {
								labarugiUnitTabSyncAfterSave(group, data.nominal_formatted || input.value);
							}
							if (data.derived_peer_values && group.getAttribute('data-unit')) {
								labarugiUnitTabSyncApplyDerivedPeerValues(group.getAttribute('data-unit'), data.derived_peer_values);
							}
							labarugiTriggerRecalcFromGroup(group);
						} else {
							if (status) {
								status.textContent = (data && data.message) ? data.message : 'Gagal simpan';
								status.className = 'labarugi-grid-status is-error';
							}
							labarugiGridToggleSave(group);
						}
					})
					.catch(function() {
						if (status) {
							status.textContent = 'Gagal koneksi';
							status.className = 'labarugi-grid-status is-error';
						}
						labarugiGridToggleSave(group);
					});
				});
			});

			labarugiInitUnitPublish();

			function labarugiInitDualHorizontalScroll() {
				document.querySelectorAll('.labarugi-unit-grid-scroll-shell').forEach(function(shell) {
					if (shell.getAttribute('data-dual-scroll-ready') === '1') { return; }
					var top = shell.querySelector('.labarugi-unit-grid-scroll-top');
					var main = shell.querySelector('.labarugi-unit-grid-scroll-main');
					var topInner = shell.querySelector('.labarugi-unit-grid-scroll-top-inner');
					var table = shell.querySelector('.labarugi-unit-grid-table');
					if (!top || !main || !topInner || !table) { return; }

					var syncing = false;

					function syncWidth() {
						topInner.style.width = table.scrollWidth + 'px';
					}

					function syncFrom(source, target) {
						if (syncing) { return; }
						syncing = true;
						target.scrollLeft = source.scrollLeft;
						syncing = false;
					}

					top.addEventListener('scroll', function() { syncFrom(top, main); });
					main.addEventListener('scroll', function() { syncFrom(main, top); });

					shell._labarugiSyncWidth = syncWidth;
					shell.setAttribute('data-dual-scroll-ready', '1');
					syncWidth();
				});
			}

			window.labarugiRefreshDualScroll = function() {
				document.querySelectorAll('.labarugi-unit-grid-scroll-shell').forEach(function(shell) {
					if (shell._labarugiSyncWidth) {
						shell._labarugiSyncWidth();
					}
				});
			};

			labarugiInitDualHorizontalScroll();
			window.addEventListener('resize', function() {
				if (typeof window.labarugiRefreshDualScroll === 'function') {
					window.labarugiRefreshDualScroll();
				}
			});
			setTimeout(window.labarugiRefreshDualScroll, 200);
			setTimeout(window.labarugiRefreshDualScroll, 800);

			labarugiInitAutoCalc();
			setTimeout(labarugiInitAutoCalc, 300);

			window.labarugiInitAutoCalc = labarugiInitAutoCalc;
			window.labarugiTriggerRecalcFromGroup = labarugiTriggerRecalcFromGroup;

			function labarugiOnSourceFieldChange(ev) {
				var input = ev.target;
				if (!input || !input.classList || !input.classList.contains('labarugi-grid-input')) { return; }
				if (input.classList.contains('labarugi-calc-input') || input.readOnly) { return; }
				var group = input.closest('.labarugi-grid-input-group');
				if (!group || group.getAttribute('data-is-calculated') === '1') { return; }
				labarugiTriggerRecalcFromGroup(group);
			}

			document.addEventListener('input', labarugiOnSourceFieldChange, true);
			document.addEventListener('change', labarugiOnSourceFieldChange, true);
			document.addEventListener('keyup', labarugiOnSourceFieldChange, true);

			document.querySelectorAll('.labarugi-tab-btn').forEach(function(btn) {
				btn.addEventListener('click', function() {
					setTimeout(labarugiInitAutoCalc, 100);
				});
			});
		})();
		</script>

		<script>
		(function() {
			function bootLabarugiKet() {
				var $ = window.jQuery;
				if (!$ || !$.fn || !$.fn.DataTable) {
					setTimeout(bootLabarugiKet, 50);
					return;
				}

				var cfg = window.labarugiKeteranganConfig || {};
				var currentJenisTab = 'rinci';
				var groupOptions = (cfg.groupOptions || []).slice();
				var ketDataTable = null;
				var modalEl = document.getElementById('modalLabarugiKeterangan');
				var tbodyEl = document.getElementById('labarugiKetTableBody');
				var tabLabelEl = document.getElementById('labarugiKetModalTabLabel');
				var btnTambah = document.getElementById('labarugiKetBtnTambah');

				if (!modalEl || !tbodyEl || !cfg.listUrlBase || !cfg.saveUrl) { return; }

				if (modalEl.parentElement !== document.body) {
					document.body.appendChild(modalEl);
				}

				function escHtml(val) {
					return String(val || '')
						.replace(/&/g, '&amp;')
						.replace(/</g, '&lt;')
						.replace(/>/g, '&gt;')
						.replace(/"/g, '&quot;');
				}

				function mergeGroupOptions(extra) {
					(extra || []).forEach(function(opt) {
						var val = String(opt || '').trim();
						if (val && groupOptions.indexOf(val) === -1) {
							groupOptions.push(val);
						}
					});
				}

				function buildSelectOptions(options, selected, allowEmpty) {
					var html = allowEmpty ? '<option value="">— Pilih Group —</option>' : '';
					options.forEach(function(opt) {
						var sel = (opt === selected) ? ' selected' : '';
						html += '<option value="' + escHtml(opt) + '"' + sel + '>' + escHtml(opt) + '</option>';
					});
					if (selected && options.indexOf(selected) === -1) {
						html += '<option value="' + escHtml(selected) + '" selected>' + escHtml(selected) + '</option>';
					}
					return html;
				}

				function ketScrollHeight() {
					return Math.max(360, Math.floor(window.innerHeight * 0.58)) + 'px';
				}

				function buildRowHtml(row, rowNo, isFirst) {
					var isNew = !row.id;
					var idVal = isNew ? '' : row.id;
					var noDisplay = rowNo || 1;
					var urutanVal = row.urutan ? parseInt(row.urutan, 10) : noDisplay;
					var btnLabel = isNew ? 'Simpan' : 'Update';
					var btnClass = isNew ? 'btn-success' : 'btn-primary';
					var groupVal = row.nama_group || '';
					var upDisabled = (isFirst || isNew) ? ' disabled' : '';

					return '<tr data-id="' + escHtml(idVal) + '" data-urutan="' + escHtml(urutanVal) + '">' +
						'<td class="text-center">' + escHtml(noDisplay) + '</td>' +
						'<td class="labarugi-ket-urutan-cell">' +
							'<span class="labarugi-ket-urutan-num">' + escHtml(urutanVal) + '</span>' +
							'<button type="button" class="btn labarugi-ket-btn-urutan-up btn-ket-urutan-up" title="Naikkan urutan tampil"' + upDisabled + '>' +
								'<i class="fa fa-arrow-up" aria-hidden="true"></i>' +
							'</button>' +
						'</td>' +
						'<td><input type="text" class="form-control form-control-sm labarugi-ket-row-input ket-uuid" value="' + escHtml(row.uuid_nama_keterangan || '') + '" placeholder="Auto jika kosong"></td>' +
						'<td><input type="text" class="form-control form-control-sm labarugi-ket-row-input ket-nama" value="' + escHtml(row.nama_keterangan || '') + '"></td>' +
						'<td><select class="form-control form-control-sm labarugi-ket-row-select ket-group">' + buildSelectOptions(groupOptions, groupVal, true) + '</select></td>' +
						'<td><select class="form-control form-control-sm labarugi-ket-row-select ket-status-keterangan">' + buildSelectOptions(cfg.statusKeteranganOptions || [], row.status_keterangan || 'keterangan', false) + '</select></td>' +
						'<td><select class="form-control form-control-sm labarugi-ket-row-select ket-status-labarugi">' + buildSelectOptions(cfg.statusLabarugiOptions || [], row.status_labarugi || currentJenisTab, false) + '</select></td>' +
						'<td><input type="text" class="form-control form-control-sm labarugi-ket-row-input ket-keterangan" value="' + escHtml(row.keterangan || '') + '"></td>' +
						'<td class="text-center"><button type="button" class="btn btn-sm labarugi-ket-btn-save-row ' + btnClass + ' btn-ket-save">' + btnLabel + '</button></td>' +
						'</tr>';
				}

				function destroyDataTable() {
					if (ketDataTable) {
						ketDataTable.destroy();
						ketDataTable = null;
					}
				}

				function initDataTable() {
					ketDataTable = $('#tblLabarugiKeterangan').DataTable({
						paging: true,
						pageLength: 15,
						lengthChange: true,
						searching: true,
						ordering: false,
						info: true,
						autoWidth: false,
						deferRender: true,
						scrollX: true,
						scrollY: ketScrollHeight(),
						scrollCollapse: false,
						dom: '<"labarugi-ket-dt-top"lf>rt<"labarugi-ket-dt-bottom"ip>',
						columnDefs: [
							{ targets: 0, className: 'text-center', orderable: false },
							{ targets: 1, className: 'text-center labarugi-ket-urutan-cell', orderable: false },
							{ targets: 8, orderable: false, className: 'text-center' }
						],
						language: {
							search: 'Cari:',
							lengthMenu: 'Tampilkan _MENU_ baris',
							info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
							paginate: { previous: 'Sebelum', next: 'Berikut' },
							zeroRecords: 'Data tidak ditemukan'
						}
					});
					ketDataTable.columns.adjust().draw(false);
				}

				function renderRows(rows) {
					destroyDataTable();
					tbodyEl.innerHTML = '';
					(rows || []).forEach(function(row, idx) {
						if (row.nama_group) { mergeGroupOptions([row.nama_group]); }
						tbodyEl.insertAdjacentHTML('beforeend', buildRowHtml(row, idx + 1, idx === 0));
					});
					initDataTable();
				}

				function loadRows() {
					var url = cfg.listUrlBase + '/' + encodeURIComponent(currentJenisTab);
					return fetch(url, { credentials: 'same-origin' })
						.then(function(res) { return res.json(); })
						.then(function(data) {
							if (!data || !data.ok) {
								throw new Error((data && data.message) ? data.message : 'Gagal memuat data.');
							}
							mergeGroupOptions(data.group_options || []);
							renderRows(data.data || []);
						});
				}

				function collectRowData(tr) {
					return {
						id: tr.getAttribute('data-id') || '',
						uuid_nama_keterangan: tr.querySelector('.ket-uuid') ? tr.querySelector('.ket-uuid').value : '',
						nama_keterangan: tr.querySelector('.ket-nama') ? tr.querySelector('.ket-nama').value : '',
						nama_group: tr.querySelector('.ket-group') ? tr.querySelector('.ket-group').value : '',
						status_keterangan: tr.querySelector('.ket-status-keterangan') ? tr.querySelector('.ket-status-keterangan').value : '',
						status_labarugi: tr.querySelector('.ket-status-labarugi') ? tr.querySelector('.ket-status-labarugi').value : '',
						keterangan: tr.querySelector('.ket-keterangan') ? tr.querySelector('.ket-keterangan').value : ''
					};
				}

				function moveRowUp(tr, btn) {
					var rowId = tr.getAttribute('data-id') || '';
					if (!rowId || !cfg.moveUpUrl) {
						return;
					}

					btn.disabled = true;
					var formData = new FormData();
					formData.append('id', rowId);

					fetch(cfg.moveUpUrl, { method: 'POST', body: formData, credentials: 'same-origin' })
						.then(function(res) { return res.json(); })
						.then(function(data) {
							btn.disabled = false;
							if (data && data.ok) {
								loadRows().catch(function(err) {
									Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Gagal memuat ulang urutan.' });
								});
							} else {
								Swal.fire({
									icon: 'warning',
									title: 'Perhatian',
									text: (data && data.message) ? data.message : 'Urutan tidak dapat diubah.'
								});
							}
						})
						.catch(function() {
							btn.disabled = false;
							Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan koneksi.' });
						});
				}

				function saveRow(tr, btn) {
					var payload = collectRowData(tr);
					if (!payload.nama_keterangan.replace(/\s/g, '')) {
						Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Nama keterangan wajib diisi.' });
						return;
					}

					btn.disabled = true;
					var formData = new FormData();
					if (payload.id) { formData.append('id', payload.id); }
					formData.append('uuid_nama_keterangan', payload.uuid_nama_keterangan);
					formData.append('nama_keterangan', payload.nama_keterangan);
					formData.append('nama_group', payload.nama_group);
					formData.append('status_keterangan', payload.status_keterangan);
					formData.append('status_labarugi', payload.status_labarugi);
					formData.append('keterangan', payload.keterangan);

					fetch(cfg.saveUrl, { method: 'POST', body: formData, credentials: 'same-origin' })
						.then(function(res) { return res.json(); })
						.then(function(data) {
							btn.disabled = false;
							if (data && data.ok) {
								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: data.message || 'Data berhasil disimpan.',
									timer: 1800,
									showConfirmButton: false
								}).then(function() {
									window.location.reload();
								});
							} else {
								Swal.fire({ icon: 'error', title: 'Gagal', text: (data && data.message) ? data.message : 'Gagal menyimpan data.' });
							}
						})
						.catch(function() {
							btn.disabled = false;
							Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan koneksi.' });
						});
				}

				document.querySelectorAll('.labarugi-btn-setting-keterangan').forEach(function(btn) {
					btn.addEventListener('click', function() {
						currentJenisTab = btn.getAttribute('data-jenis-tab') || 'rinci';
						var tabLabel = btn.getAttribute('data-tab-label') || currentJenisTab;
						if (tabLabelEl) { tabLabelEl.textContent = tabLabel; }
						$(modalEl).modal('show');
						loadRows().catch(function(err) {
							Swal.fire({ icon: 'error', title: 'Gagal', text: err.message || 'Gagal memuat data keterangan.' });
						});
					});
				});

				if (btnTambah) {
					btnTambah.addEventListener('click', function() {
						var newRow = {
							id: '',
							uuid_nama_keterangan: '',
							nama_keterangan: '',
							nama_group: '',
							status_keterangan: 'keterangan',
							status_labarugi: currentJenisTab,
							keterangan: '',
							urutan: 0
						};
						if (ketDataTable) {
							ketDataTable.destroy();
							ketDataTable = null;
						}
						tbodyEl.insertAdjacentHTML('afterbegin', buildRowHtml(newRow, 1, true));
						initDataTable();
					});
				}

				tbodyEl.addEventListener('click', function(e) {
					var btnUp = e.target.closest('.btn-ket-urutan-up');
					if (btnUp) {
						var trUp = btnUp.closest('tr');
						if (trUp) {
							moveRowUp(trUp, btnUp);
						}
						return;
					}

					var btn = e.target.closest('.btn-ket-save');
					if (!btn) { return; }
					var tr = btn.closest('tr');
					if (!tr) { return; }
					saveRow(tr, btn);
				});

				$(modalEl).on('shown.bs.modal', function() {
					if (ketDataTable) {
						ketDataTable.columns.adjust();
					}
				});

				$(modalEl).on('hidden.bs.modal', function() {
					destroyDataTable();
					tbodyEl.innerHTML = '';
				});

				$(window).on('resize.labarugiKet', function() {
					if (!ketDataTable) { return; }
					var api = ketDataTable;
					var settings = api.settings()[0];
					if (settings && settings.oScroll) {
						settings.oScroll.sY = ketScrollHeight();
						$('.dataTables_scrollBody', $(modalEl)).css('max-height', ketScrollHeight());
					}
					api.columns.adjust();
				});
			}

			bootLabarugiKet();
		})();
		</script>

		<?php if (!empty($bulan_transaksi) && (int) $bulan_transaksi > 0 && !empty($labarugi_can_publish)) { ?>
		<div class="card-header">
			<div class="row">
				<div class="form-group">
					<div class="row">
						<div class="col-12" align="center" style="margin-top: 15px; margin-bottom: 15px;">
							<?php if ($this->session->flashdata('message')) { ?>
								<div class="alert alert-info alert-dismissible fade show" role="alert" style="max-width: 500px; margin: 0 auto 15px;">
									<?php echo $this->session->flashdata('message'); ?>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
							<?php } ?>
							<?php if (!empty($labarugi_is_published)) { ?>
								<form action="<?php echo site_url('Tbl_laba_rugi/publish_labarugi/' . $tahun_neraca . '/' . $bulan_transaksi); ?>" method="post" style="display: inline;">
									<input type="hidden" name="action" value="cancel">
									<button type="submit" class="btn labarugi-btn-cancel-publish" style="background-color: #dc3545; border-color: #dc3545; color: #fff; min-width: 220px; padding: 12px 24px; font-weight: bold;">
										<i class="fa fa-times-circle"></i> Cancel Publish
									</button>
								</form>
								<div class="labarugi-print-unit-actions mt-3">
									<a href="<?php echo site_url('Tbl_laba_rugi/labarugi_print_unit/' . (int) $tahun_neraca . '/' . (int) $bulan_transaksi . '/rinci'); ?>"
										class="btn btn-info labarugi-btn-print-unit"
										target="_blank"
										title="Cetak Laba Rugi Per Unit Rinci">
										<i class="fa fa-print"></i> Cetak Laba Rugi Per Unit (Rinci)
									</a>
									<a href="<?php echo site_url('Tbl_laba_rugi/labarugi_print_unit/' . (int) $tahun_neraca . '/' . (int) $bulan_transaksi . '/sederhana'); ?>"
										class="btn btn-primary labarugi-btn-print-unit ml-2"
										target="_blank"
										title="Cetak Laba Rugi Per Unit Sederhana">
										<i class="fa fa-print"></i> Cetak Laba Rugi Per Unit (Sederhana)
									</a>
								</div>
							<?php } else { ?>
								<form action="<?php echo site_url('Tbl_laba_rugi/publish_labarugi/' . $tahun_neraca . '/' . $bulan_transaksi); ?>" method="post" style="display: inline;">
									<input type="hidden" name="action" value="publish">
									<button type="submit" class="btn labarugi-btn-publish" style="background-color: #28a745; border-color: #28a745; color: #fff; min-width: 220px; padding: 12px 24px; font-weight: bold;" <?php echo !empty($labarugi_has_record) ? '' : 'disabled'; ?>>
										<i class="fa fa-upload"></i> Publish
									</button>
								</form>
								<?php if (empty($labarugi_has_record)) { ?>
									<p class="text-muted small mt-2 mb-0">Simpan data terlebih dahulu sebelum publish.</p>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>

		<!-- <div class="card card-success"> -->
		<div class="card-header">

			<div class="row">
				<!-- <div class="col-12" text-align="center"> <strong><label for="nmrsj">Jumlah Pembayaran </label></strong></div> -->




				<div class="form-group">

					<div class="row">
						<div class="col-12" align="center">


							<!-- <button onclick="history.back()">&#8592; Back</button> -->
							
							<a href="<?php echo base_url('index.php/Tbl_laba_rugi'); ?>">< Back</a>

						</div>

					</div>

				</div>


			</div>

		</div>
	</div>


</form>

<?php

function penyebut($nilai)
{
	$nilai = abs($nilai);
	$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " " . $huruf[$nilai];
	} else if ($nilai < 20) {
		$temp = penyebut($nilai - 10) . " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
	}
	return $temp;
}

function terbilang($nilai)
{
	if ($nilai < 0) {
		$hasil = "minus " . trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}
	return $hasil;
}


// $angka = 1530093;
// echo terbilang($angka);

?>


<!-- </body> -->
