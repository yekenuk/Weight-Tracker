<?php

    defined('ABSPATH') or die("Jog on!");

    function ws_ls_settings_page_group() {

        ws_ls_data_table_enqueue_scripts();

    ?>
        <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-3">
                <div id="post-body-content">

                    <div class="meta-box-sortables ui-sortable">
                        <?php
                            if ( false === WS_LS_IS_PRO ) {
                                ws_ls_display_pro_upgrade_notice();
                            }
                        ?>
                        <div class="postbox">
                            <h3 class="hndle"><span><?php echo __('Manage Groups', WE_LS_SLUG); ?></span></h3>
                            <div style="padding: 0px 15px 0px 15px">

                                <p><?php echo __('Add or remove Groups that users can be assigned to.', WE_LS_SLUG); ?></p>

                                <h4><?php echo __('Add a new group', WE_LS_SLUG); ?></h4>
                                <?php

                                    $new_group = ws_ls_ajax_post_value( 'group' );

                                    if ( false === empty( $new_group ) ) {
	                                    ws_ls_groups_add( $new_group );
                                    }

                                ?>
                                <form method="post">
                                    <input type="text" name="group" size="30" maxlength="40" />
                                    <input type="submit" value="Add" class="button"/>
                                </form>

                                <h4><?php echo __('Existing groups', WE_LS_SLUG); ?></h4>
                                <table class="ws-ls-settings-groups-list-ajax table ws-ls-loading-table" id="groups-list"
                                       data-paging="true"
                                       data-filtering="false"
                                       data-sorting="true"
                                       data-editing-allow-add="false"
                                       data-editing-allow-edit="false"
                                       data-cascade="true"
                                       data-toggle="true"
                                       data-use-parent-width="true">
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
        </div>
    <?php

    }
