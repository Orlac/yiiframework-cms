<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!-- generated-on="<?php echo $time; ?>" --><!-- Total Records: <?php echo count( $rows ); ?> -->
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">	
	<?php if( ( is_array($rows) && count($rows) ) ): ?>
		<?php foreach( $rows as $row ): ?>
			<url>
				<loc><?php echo $row['loc']; ?></loc>
				<lastmod><?php echo $row['lastmod']; ?></lastmod>
				<changefreq><?php echo $row['changefreq']; ?></changefreq>
				<priority><?php echo $row['priority']; ?></priority>
			</url>
		<?php endforeach; ?>
	<?php endif; ?>
</urlset>