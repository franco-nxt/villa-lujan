<?php 
$__regulations__ =  DB::instance()->query("SELECT name, src FROM regulations ORDER BY upload_date DESC ")->result();
?>
<nav class="navbar">
	<div class="navbar__brand">
		<a href="<?= __ROOT__ ?>/quincho">
			<img src="<?= __ROOT__ ?>/images/logo.png" />
		</a>
	</div>
	<div class="navbar__collapse">

		<ul class="navbar__item navbar__item-menu">
			<li>
				<a href="<?= __ROOT__ ?>/quincho">Quincho</a>
				<ul>
					<!-- <li><a href="<?= __ROOT__ ?>/quincho">Reservar</a></li> -->
					<li><a href="<?= __ROOT__ ?>/quincho/info">Reportes</a></li>
					<li><a href="<?= __ROOT__ ?>/admin/quincho/info">Reportes de otros usuarios</a></li>
				</ul>
			</li>
		</ul>

		<ul class="navbar__item navbar__item-menu">
			<li>
				<a href="<?= __ROOT__ ?>/propiedades">Residentes Temporarios</a>
				<ul>
					<!-- <li><a href="<?= __ROOT__ ?>/propiedades">Reservar</a></li> -->
					<li><a href="<?= __ROOT__ ?>/propiedades/info">Reportes</a></li>
					<li><a href="<?= __ROOT__ ?>/admin/propiedades">Reservar por otro usuario</a></li>
					<li><a href="<?= __ROOT__ ?>/admin/propiedades/info">Reportes de otros usuarios</a></li>
				</ul>
			</li>
		</ul>
		<ul class="navbar__item navbar__item-menu">
			<li>
				<a href="<?= __ROOT__ ?>/admin/propietarios">Residentes</a>
				<ul>
					<li><a href="<?= __ROOT__ ?>/admin/propietarios/nuevo">Nuevo</a></li>
				</ul>
			</li>
		</ul>
		<ul class="navbar__item navbar__item-menu navbar__item-rules">
			<li>
				<a href="#" class="navbar__item "><img src="<?= __ROOT__ ?>/images/pdf.png" alt="Reglamento Interno">Reglamentos</a>
				<ul>
					<?php foreach ($__regulations__ as $reg): ?>
					<li><a href="<?= $reg->src ?>" target="_blank"><?= $reg->name ?></a></li>
					<?php endforeach ?>
					<li><a href="<?= __ROOT__ ?>/admin/reglamentos/">Editar reglamentos</a></li>
				</ul>
			</li>
		</ul>
		<a href="<?= __ROOT__ ?>/logout" class="navbar__item">Salir</a>
	</div>
	<div class="navbar__toggle-wrap">
		<a href="#" class="navbar__toggle">
			<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDU2IDU2IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1NiA1NjsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSIzMnB4IiBoZWlnaHQ9IjMycHgiPgo8cGF0aCBkPSJNMjgsMEMxMi41NjEsMCwwLDEyLjU2MSwwLDI4czEyLjU2MSwyOCwyOCwyOHMyOC0xMi41NjEsMjgtMjhTNDMuNDM5LDAsMjgsMHogTTQwLDQxSDE2Yy0xLjEwNCwwLTItMC44OTYtMi0yczAuODk2LTIsMi0yICBoMjRjMS4xMDQsMCwyLDAuODk2LDIsMlM0MS4xMDQsNDEsNDAsNDF6IE00MCwzMEgxNmMtMS4xMDQsMC0yLTAuODk2LTItMnMwLjg5Ni0yLDItMmgyNGMxLjEwNCwwLDIsMC44OTYsMiwyUzQxLjEwNCwzMCw0MCwzMHogICBNNDAsMTlIMTZjLTEuMTA0LDAtMi0wLjg5Ni0yLTJzMC44OTYtMiwyLTJoMjRjMS4xMDQsMCwyLDAuODk2LDIsMlM0MS4xMDQsMTksNDAsMTl6IiBmaWxsPSIjY2Q2ZTQ4Ii8+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=" />
		</a>
	</div>
</nav>