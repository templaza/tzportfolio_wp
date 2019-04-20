<?php
use tp\lib\utilities\AddOnHelper;

$form       = $this -> data_edit['form'];
$textDomain = $this -> data_edit['textDomain'];
?>
<script type="text/javascript">
    jQuery( document ).ready( function() {
        postboxes.add_postbox_toggles( '<?php echo $this->data_edit['screen_id']; ?>' );
    });
</script>

<div class="wrap">
	<h2>
		<?php echo ( 'add' == $_GET['action'] ) ? __( 'Add New Role', 'tz-portfolio' ) : __( 'Edit AddOn Setting', 'tz-portfolio' ) ?>
        <input type="button" value="<?php echo ! empty( $_GET['id'] ) ? __( 'Update', 'tz-portfolio' ) : __( 'Create Role', 'tz-portfolio' ) ?>" class="button-primary" id="create_role" name="create_role" style="vertical-align: top;" onclick="$('#tp_edit_role').submit();">
        <input type="button" class="cancel_popup button" value="<?php _e( 'Cancel', 'tz-portfolio' ) ?>" onclick="window.location = '<?php echo add_query_arg( array( 'page' => 'tzportfolio-acl' ), admin_url( 'admin.php' ) ) ?>';" />
	</h2>

	<?php if ( ! empty( $_GET['msg'] ) ) {
		switch( $_GET['msg'] ) {
			case 'a':
				echo '<div id="message" class="updated fade"><p>' . __( 'User Role <strong>Added</strong> Successfully.', 'tz-portfolio' ) . '</p></div>';
				break;
			case 'u':
				echo '<div id="message" class="updated fade"><p>' . __( 'User Role <strong>Updated</strong> Successfully.', 'tz-portfolio' ) . '</p></div>';
				break;
		}
	}

	if ( ! empty( $error ) ) { ?>
		<div id="message" class="error fade">
			<p><?php echo $error ?></p>
		</div>
	<?php } ?>

	<form id="tp-addons__settings" action="" method="post">
<!--        --><?php //var_dump($this ); die();?>
<!--		<input type="hidden" name="role[id]" value="--><?php //echo isset( $_GET['id'] ) ? esc_attr( $_GET['id'] ) : '' ?><!--" />-->
<!--		--><?php //wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder">
				<div id="post-body-content">
					<div id="titlediv">
						<div id="titlewrap">
                            <div class="accordion-section-content">
                                <ul id="posttype-page-tabs" class="posttype-tabs add-menu-item-tabs">
                                    <li class="tabs">
                                        <a class="nav-tab-link" data-type="tabs-panel-posttype-page-most-recent" href="#">
                                            Most Recent				</a>
                                    </li>
                                    <li class="">
                                        <a class="nav-tab-link" data-type="page-all" href="#">
                                            View All				</a>
                                    </li>
                                    <li class="">
                                        <a class="nav-tab-link" data-type="tabs-panel-posttype-page-search" href="#">
                                            Search				</a>
                                    </li>
                                </ul>
                                <div id="tabs-panel-posttype-page-most-recent" class="tabs-panel tabs-panel-active">
                                    <ul id="pagechecklist-most-recent" class="categorychecklist form-no-clear">
                                        <li><label class="menu-item-title"><input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="2"> Sample Page</label><input type="hidden" class="menu-item-db-id" name="menu-item[-1][menu-item-db-id]" value="0"><input type="hidden" class="menu-item-object" name="menu-item[-1][menu-item-object]" value="page"><input type="hidden" class="menu-item-parent-id" name="menu-item[-1][menu-item-parent-id]" value="0"><input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="post_type"><input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Sample Page"><input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="http://localhost:81/wordpress_tppcore/sample-page/"><input type="hidden" class="menu-item-target" name="menu-item[-1][menu-item-target]" value=""><input type="hidden" class="menu-item-attr_title" name="menu-item[-1][menu-item-attr_title]" value=""><input type="hidden" class="menu-item-classes" name="menu-item[-1][menu-item-classes]" value=""><input type="hidden" class="menu-item-xfn" name="menu-item[-1][menu-item-xfn]" value=""></li>
                                    </ul>
                                </div>
                                <div class="tabs-panel tabs-panel-inactive" id="tabs-panel-posttype-page-search">
                                    <p class="quick-search-wrap">
                                        <label for="quick-search-posttype-page" class="screen-reader-text">Search</label>
                                        <input type="search" class="quick-search" value="" name="quick-search-posttype-page" id="quick-search-posttype-page">
                                        <span class="spinner"></span>
                                        <input type="submit" name="submit" id="submit-quick-search-posttype-page" class="button button-small quick-search-submit hide-if-js" value="Search">			</p>

                                    <ul id="page-search-checklist" data-wp-lists="list:page" class="categorychecklist form-no-clear">
                                    </ul>
                                </div>
                            </div>

                            <?php
                            $fieldSets   = $form -> getFieldsets();
                            if(count($fieldSets)){
                                $tabContents    = array();
                                ?>
                                <h2 class="nav-tab-wrapper">
<!--                                    <a href="#tab1" class="nav-tab">Tab #1</a>-->
<!--                                    <a href="#tab2" class="nav-tab nav-tab-active">Tab #2</a>-->
<!--                                    <a href="#tab3" class="nav-tab">Tab #3</a>-->
                                    <?php foreach($fieldSets as $name => $fieldSet) {

                                        ?>
                                        <a href="#tp-addons-tabs__<?php echo $name; ?>" class="nav-tab"><?php echo __($fieldSet -> label?$fieldSet -> label:'Basic', $textDomain);?></a>

                                        <?php
                                        $fields = $form -> getFieldset($name);
                                        if(count($fields)){
                                            ob_start();
                                        ?>
                                            <div id="tp-addons-tabs__<?php echo $name;?>">

                                            <?php foreach($fields as $field){
                                                    echo $field -> __get('label');
                                                    echo $field -> __get('input');
                                            ?>

                                            <?php } ?>

                                            </div>
                                        <?php
                                            $tabContents[] = ob_get_contents();
                                            ob_end_clean();
                                        } ?>
                                    <?php } ?>
                                </h2>
                                <?php if(count($tabContents)){
                                    echo implode("", $tabContents);
                                } ?>
                            <?php
                            }
                            ?>
                            <label for="title" class="screen-reader-text"><?php _e( 'Title', 'tz-portfolio' ) ?></label>
<!--							--><?php //if ( 'add' == $_GET['action'] ) { ?>
<!--								<label for="title" class="screen-reader-text">--><?php //_e( 'Title', 'tz-portfolio' ) ?><!--</label>-->
<!--								<input type="text" name="role[name]" placeholder="--><?php //_e( 'Enter Title Here', 'tz-portfolio' ) ?><!--" id="title" value="--><?php //echo isset( $this->data_edit['data']['name'] ) ? $this->data_edit['data']['name'] : '' ?><!--" />-->
<!--							--><?php //} else { ?>
<!--								<input type="hidden" name="role[name]" value="--><?php //echo isset( $this->data_edit['data']['name'] ) ? $this->data_edit['data']['name'] : '' ?><!--" />-->
<!--								<h1 style="float: left;width:100%;">--><?php //echo isset( $this->data_edit['data']['name'] ) ? $this->data_edit['data']['name'] : '' ?><!--</h1>-->
<!--							--><?php //} ?>
						</div>
					</div>
				</div>
				<div id="postbox-container" class="postbox-container">
					<?php do_meta_boxes( 'tp_role_meta', 'normal', array( 'data' => $this->data_edit['data'], 'option' => $this->data_edit['option'] ) ); ?>
				</div>
			</div>
		</div>
	</form>
</div>