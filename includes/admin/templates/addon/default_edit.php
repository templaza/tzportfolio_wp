<?php

use tp\lib\Language\TPLanguage;
use tp\lib\utilities\AddOnHelper;

$form       = $this -> data_edit['form'];
$textDomain = $this -> data_edit['textDomain'];

wp_enqueue_script( 'jquery-ui-tabs');

//wp_enqueue_script( 'tp-addon', plugin_dir_url(tp_plugin).'includes/admin/assets/js/tp-addon.js', array('jquery-ui-tabs') , '1.0.0', false);
wp_enqueue_script( 'tp-addon', plugin_dir_url(tp_plugin).'includes/admin/assets/js/tp-addon.js', array('jquery-ui-tabs') , '1.0.0', false);
?>
<div class="wrap">
	<h1>
		<?php echo ( 'add' == $_GET['action'] ) ? __( 'Add New AddOn', 'tz-portfolio' ) : __( 'Edit AddOn Setting', 'tz-portfolio' ) ?>
	</h1>

    <?php if($item = $this -> data_edit['item']){ ?>
        <h2><?php echo $item['Name']; ?></h2>
        <i><?php echo TPLanguage::sprintf('Version %s',$item['Version']); ?></i>
        <p><?php echo $item['Description']; ?></p>
    <?php } ?>

	<?php
    if ( ! empty( $_GET['msg'] ) ) {
		switch( $_GET['msg'] ) {
			case 'a':
				echo '<div id="message" class="updated fade"><p>' . __( 'Add-On\'s Options <strong>Added</strong> Successfully.', 'tz-portfolio' ) . '</p></div>';
				break;
			case 'u':
				echo '<div id="message" class="updated fade"><p>' . __( 'Add-On\'s Options <strong>Updated</strong> Successfully.', 'tz-portfolio' ) . '</p></div>';
				break;
			case 'e':
				echo '<div id="message" class="error fade"><p>' . __( 'Can Not <strong>Updated</strong>.', 'tz-portfolio' ) . '</p></div>';
				break;
		}
	}

	if ($error) {
	    ?>
		<div id="message" class="<?php echo $message['type']?$message['type']:'updated'; ?> fade">
			<p><?php echo $error ?></p>
		</div>
	<?php } ?>

	<form id="tp-addons__settings" action="" method="post">

        <input type="submit" value="<?php echo ! empty( $_GET['id'] ) ? __( 'Update Add-On', 'tz-portfolio' ) : __( 'Create Role', 'tz-portfolio' ) ?>" class="button-primary" id="create_role" name="create_role" style="vertical-align: top;">
        <input type="button" class="cancel_popup button" value="<?php _e( 'Cancel', 'tz-portfolio' ) ?>" onclick="window.location = '<?php echo add_query_arg( array( 'page' => $_GET['page'] ), admin_url( 'admin.php' ) ) ?>';" />
		<div class="wrap mt-1" data-tp-tabs>
            <?php
            $fieldSets   = $form -> getFieldsets();
            if(count($fieldSets)){
                $tabContents    = array();
                ?>
                <ul class="nav-tab-wrapper tp-nav-tab-wrapper">
                    <?php foreach($fieldSets as $name => $fieldSet) {

                        ?>
                        <li class="">
                            <a href="#tp-addons-tabs__<?php echo $name; ?>" class="nav-tab"><?php
                                echo __($fieldSet -> label?$fieldSet -> label:'Basic', $textDomain);
                                ?></a>
                        </li>

                        <?php
                        $fields = $form -> getFieldset($name);
                        if(count($fields)){
                            ob_start();
                        ?>
                            <div id="tp-addons-tabs__<?php echo $name;?>">

                                <div class="tp-form-horizontal">
                                    <?php foreach($fields as $field){
                                        if(strtolower($field -> __get('type')) != 'spacer') {
                                            echo $field->renderField();
                                        }else{

                                            $hr = (string) $field -> getAttribute('hr');
                                    ?>
                                        <?php if($hr != true){ ?>
                                        <div class="form-group ">
                                        <?php } ?>
                                            <?php echo $field -> __get('label');?>
                                        <?php if($hr != true){ ?>
                                        </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php } ?>
                                </div>

                            </div>
                        <?php
                            $tabContents[] = ob_get_contents();
                            ob_end_clean();
                        } ?>
                    <?php } ?>
                </ul>
                <?php if(count($tabContents)){
                    echo implode("", $tabContents);
                } ?>
            <?php
            }
            ?>
		</div>
	</form>
</div>