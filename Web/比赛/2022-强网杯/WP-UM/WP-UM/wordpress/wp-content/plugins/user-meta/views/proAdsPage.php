<?php namespace UserMeta; ?>
<?php global $userMeta; ?>

<div class="wrap">
	<h1>
		<?= __('More features on Pro version', $userMeta->name)?>
		<a class='button-primary' href='<?=$userMeta->website?>'>Get User Meta
			Pro</a> <a class='button-primary' href='https://demo.user-meta.com'>Live
			Demo</a>
	</h1>

	<br />

	<div class="container-fluid">
		<div class="row">

			<div class="col-sm-10">
				<?=panel('Extra fields on backend profile', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-07.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Bulk users export with extra fields', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-09.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Bulk users import with extra fields', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-10.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Customize all WordPress generated email. Include extra field to email subject or body', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-11.png" /><img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-12.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Admin approval / Email verification', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-14.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Role based redirection', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-15.png" />')?>
			</div>

			<div class="col-sm-10">
				<?=panel('Free add-ons with pro version', '<img
						src="https://s3.amazonaws.com/user-meta/public/v1/screenshot-17.png" />')?>
			</div>

<?php
$more = "
				<li>Customize email notification with including extra field's data.</li>
				<li>Register new blog on multisite.</li>
				<li>Get 8 <a href='{$userMeta->website}/add-ons/'>add-ons</a>.</li>
                <div style='padding-left: 20px'>
                    <li>User Listing add-on</li>
                    <li>WooCommerce Integration add-on</li>
                    <li>WPML Integration add-on</li>
                    <li>BuddyPress xProfile Export add-on</li>
                    <li>Switch filter or action hooks add-on</li>
                    <li>Override default WP emails add-on</li>
                    <li>Personalize default User Meta forms add-on</li>
                    <li>Restrict Content add-on</li>
                </div>
    <center>
        <a class='button-primary' href='{$userMeta->website}'>Get User Meta Pro</a>
        <a class='button-primary' href='https://demo.user-meta.com'>Live Demo</a>
    </center>
    ";
?>

			<div class="col-sm-10">
				<?=panel('And More...', $more)?>
			</div>

		</div>
	</div>
</div>
