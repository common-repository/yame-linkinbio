<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://yame.be
 * @since      1.0.0
 *
 * @package    Linkinbio
 * @subpackage Linkinbio/admin/partials
 */
?>

<div class="wrap">
    <div id="app">

        <div class="tw-flex "><h1><?php _e('LinkInBio', 'yame-linkinbio'); ?></h1>
            <span class="tw-rounded-full tw-ml-2 tw-self-end tw-bg-orange-400 tw-uppercase tw-px-2 tw-py-1 tw-text-xs tw-text-white tw-font-bold tw-mr-3" v-if="updating">Updating...</span>
            <span class="tw-rounded-full tw-ml-2 tw-self-end tw-bg-green-400 tw-uppercase tw-px-2 tw-py-1 tw-text-white tw-text-xs tw-font-bold tw-mr-3" v-if="!updating" >Up to date</span></div>
        <hr>

        <div class="tw-flex">

            <div class="tw-mr-8">
                <h2 class="tw-mb-4 tw-mt-4 tw-text-2xl"><?php _e('Your bio links:', 'yame-linkinbio'); ?></h2>
                <section v-if="error_links">
                    <?php _e('Instagram API returned an error. Please try again later.', 'yame-linkinbio'); ?>
                </section>

                <section v-else>

                    <div v-if="loading_links">
                        <?php _e('Loading bio links...', 'yame-linkinbio'); ?>
                    </div>

                    <div v-else>

                        <div v-if="instagram_links.length > 0">

                            <draggable v-model="instagram_links" @start="drag=true" @end="drag=false;update()">

                                <div class="instagram-link tw-mb-2" v-for="(link, index) in instagram_links" :key="instagram_links.id">

                                    <div class="tw-max-w-md tw-w-full lg:tw-flex">
                                        <div class="tw-h-48 lg:tw-h-auto lg:tw-w-48 tw-flex-none tw-bg-cover tw-rounded-t lg:tw-rounded-t-none lg:tw-rounded-l tw-text-center tw-overflow-hidden" v-bind:style="{ backgroundImage: 'url(' + link.image + ')' }" title="Woman holding a mug">
                                        </div>
                                        <div class="tw-border-r tw-border-b tw-border-l tw-border-grey-light lg:tw-border-l-0 lg:tw-border-t lg:tw-border-grey-light tw-bg-white tw-rounded-b lg:tw-rounded-b-none lg:tw-rounded-r tw-p-4 tw-flex tw-flex-col tw-justify-between tw-leading-normal">
                                            <div class="mb-8">
                                                <div class="tw-text-black tw-font-bold tw-text-xl tw-mb-2"><input type="text" v-model="link.title" v-on:change="update" value="" placeholder="Link title"></div>
                                                <div class="tw-text-black tw-font-bold tw-mb-2"><input type="text" v-model="link.url" v-on:change="update" value="" placeholder="Link URL"></div>
                                            </div>
                                            <div class="tw-flex tw-justify-between">
                                                <p class="tw-text-sm tw-text-grey-dark" v-on:click="remove( index )">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                </p>
                                                <p class="tw-text-sm tw-text-grey-dark">
                                                    <svg class="feather feather-move sc-dnqmqq jxshSx" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-reactid="871"><polyline points="5 9 2 12 5 15"></polyline><polyline points="9 5 12 2 15 5"></polyline><polyline points="15 19 12 22 9 19"></polyline><polyline points="19 9 22 12 19 15"></polyline><line x1="2" y1="12" x2="22" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </draggable>


                        </div>
                        <div v-else>
                            <?php _e('No links were found. Click an instagram item to add a link.', 'yame-linkinbio'); ?>
                        </div>

                    </div>

                </section>

            </div>

            <div class="tw-flex-grow-0 tw-w-3/6">

                <h2 class="tw-mb-4 tw-mt-4 tw-text-2xl"><?php _e('Your Instagram username:', 'yame-linkinbio'); ?></h2>

                <p>@<input class="tw-shadow tw-appearance-none tw-border tw-rounded tw-py-2 tw-px-3 tw-text-gray-700 tw-leading-tight focus:tw-outline-none focus:tw-shadow-outline" type="text" value="" v-model="username" @change="updateAndRetrievePosts"><br></p>

                <h2 class="tw-mb-4 tw-mt-4 tw-text-2xl"><?php _e('Your bio:', 'yame-linkinbio'); ?></h2>
                <p><textarea cols="40" rows="5" class="tw-shadow tw-appearance-none tw-border tw-rounded tw-py-2 tw-px-3 tw-text-gray-700 tw-leading-tight focus:tw-outline-none focus:tw-shadow-outline" v-model="description" @change="update" placeholder="<?php _e('Add a description or leave blank', 'yame-linkinbio'); ?>"></textarea></p>

                <section v-if="error_posts">
                    <?php _e('Instagram API returned an error. Please try again later.', 'yame-linkinbio'); ?>
                </section>

                <section v-else>

                    <div v-if="loading_posts">
                        <?php _e('Loading Instagram posts...', 'yame-linkinbio'); ?>
                    </div>

                    <div v-else>

                        <h2 class="tw-mb-4 tw-mt-4 tw-text-2xl"><?php _e('Your Instagram posts:', 'yame-linkinbio'); ?></h2>

                        <p><em><?php _e('Click on an instagram post to add it as a link', 'yame-linkinbio'); ?></em></p>

                        <div class="instagram-posts">

                            <div class="instagram-post" v-for="(insta, index) in instagram_posts" :key="insta.id">
                                <div v-on:click="add( index )"><img style="max-width: 100px;" :src="insta.image"></div>
                            </div>

                        </div>

                    </div>

                </section>

            </div>

            <div>
                <h2 class="tw-mb-4 tw-mt-4 tw-text-2xl"><?php _e('Usage', 'yame-linkinbio'); ?></h2>
                <p><?php _e('Use the shortcode [linkinbio] on a page of your liking.', 'yame-linkinbio');?></p>
                <hr>
                <h2 class="tw-mb-4 tw-mt-4 tw-text-2xl"><?php _e('Thanks', 'yame-linkinbio'); ?></h2>
                <p>Thank you so much for downloading this plugin.<br>Let me know how you are using this plugin @heroyame on Twitter or @yame.be on Instagram</p>
                <br>
                <p>Missing a feature, found a bug, or just wanna say hi?<br>E-mail me: yannick@yame.be</p>
            </div>

        </div>

    </div>

    <!--
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <form action="options.php" method="post">
        <?php
        settings_fields( $this->plugin_name );
        do_settings_sections( $this->plugin_name );
        submit_button();
        ?>
    </form>-->
</div>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
