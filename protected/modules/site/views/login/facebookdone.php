<script>
facebookLoginComplete();
function facebookLoginComplete()
{
	// Redirect parent to site index
	window.opener.location.href = '<?php echo $link; ?>';
	
	// Close this window
	window.close();
}
</script>