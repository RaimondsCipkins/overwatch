<?php
//no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Images
$SocialLink[]= $this->params->get( '!', "" );
$SocialTitle[]= $this->params->get( '!', "" );


for ($j=1; $j<=40; $j++){
	$SocialLink[$j]		= $this->params->get ("SocialLink".$j,"" );
	$SocialTitle[$j]	= $this->params->get ("SocialLink".$j , "" );

}; ?>

<div id="social">
		<?php for ($i=1; $i<=40; $i++){ if ($SocialLink[$i] != null) { ?>
            <a href="<?php echo $SocialLink[$i] ?>" target="_blank" class="social-icon social_<?php echo $i ?>"></a>
        <?php }};  ?>
</div>
