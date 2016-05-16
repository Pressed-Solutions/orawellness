<?php
    /*=========
    Template Name: KBE
    =========*/
    get_header('knowledgebase');

    // load the style and script
    wp_enqueue_style ( 'kbe_theme_style' );
    if( KBE_SEARCH_SETTING == 1 ){
        wp_enqueue_script( 'kbe_live_search' );
    }

    // Classes For main content div
    if(KBE_SIDEBAR_HOME == 0) {
        $kbe_content_class = 'class="kbe_content_full"';
    } elseif(KBE_SIDEBAR_HOME == 1) {
        $kbe_content_class = 'class="kbe_content_right"';
    } elseif(KBE_SIDEBAR_HOME == 2) {
        $kbe_content_class = 'class="kbe_content_left"';
    }
?>
<div id="kbe_container">
    <!--Breadcrum-->
    <?php
        if(KBE_BREADCRUMBS_SETTING == 1){
    ?>
            <div class="kbe_breadcrum">
                <?php echo kbe_breadcrumbs(); ?>
            </div>
    <?php
        }
    ?>
    <!--/Breadcrum-->

    <!--search field-->
    <?php
        if(KBE_SEARCH_SETTING == 1){
            kbe_search_form();
        }
    ?>
    <!--/search field-->

    <!--content-->
    <div id="kbe_content" <?php echo $kbe_content_class; ?>>
        <h1><?php echo get_the_title(KBE_PAGE_TITLE) ?></h1>

        <!--leftcol-->
        <div class="kbe_leftcol">
            <div class="kbe_categories">
        <?php
            $kbe_cat_args = array(
                'orderby'       => 'terms_order',
                'order'         => 'ASC',
                'hide_empty'    => true,
                'pad_counts'    => true,
            );

            $kbe_terms = get_terms(KBE_POST_TAXONOMY, $kbe_cat_args);

            foreach($kbe_terms as $kbe_taxonomy){
                if ($kbe_taxonomy->parent === 0) {

                    $kbe_term_id = $kbe_taxonomy->term_id;
                    $kbe_term_slug = $kbe_taxonomy->slug;
                    $kbe_term_name = $kbe_taxonomy->name;
            ?>
                    <div class="kbe_category">
                        <h2>
                            <a href="<?php echo get_term_link($kbe_term_slug, 'kbe_taxonomy') ?>">
                                <span class="kbe_title"><?php echo $kbe_term_name; ?></span>
                            </a>
                        </h2>

                    <?php
                        $kbe_child_cat_args = array(
                                                'orderby'       => 'terms_order',
                                                'order'         => 'ASC',
                                                'parent'        => $kbe_term_id,
                                                'hide_empty'    => true,
                                            );

                        $kbe_child_terms = get_terms(KBE_POST_TAXONOMY, $kbe_child_cat_args);

                        if($kbe_child_terms) {
                    ?>
                        <div class="kbe_child_category" style="display: block;">
                        <?php
                            foreach($kbe_child_terms as $kbe_child_term){
                                $kbe_child_term_id = $kbe_child_term->term_id;
                                $kbe_child_term_slug = $kbe_child_term->slug;
                                $kbe_child_term_name = $kbe_child_term->name;
                        ?>
                                <h3>
                                    <a href="<?php echo get_term_link($kbe_child_term_slug, 'kbe_taxonomy') ?>">
                                        <span class="kbe_title"><?php echo $kbe_child_term_name; ?></span>
                                    </a>
                                </h3>
                                <ul class="kbe_child_article_list">
                            <?php
                                $kbe_child_post_args = array(
                                                            'post_type' => KBE_POST_TYPE,
                                                            'posts_per_page' => KBE_ARTICLE_QTY,
                                                            'orderby' => 'menu_order',
                                                            'order' => 'ASC',
                                                            'tax_query' => array(
                                                                    array(
                                                                            'taxonomy' => KBE_POST_TAXONOMY,
                                                                            'field' => 'term_id',
                                                                            'terms' => $kbe_child_term_id
                                                                    )
                                                            )
                                                    );
                                $kbe_child_post_qry = new WP_Query($kbe_child_post_args);
                                if($kbe_child_post_qry->have_posts()) :
                                    while($kbe_child_post_qry->have_posts()) :
                                        $kbe_child_post_qry->the_post();
                            ?>
                                        <li>
                                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                                <?php the_title(); ?>
                                            </a>
                                        </li>
                            <?php
                                    endwhile; ?>
                                        <li>
                                            <a href="<?php echo get_term_link($kbe_child_term_slug, 'kbe_taxonomy') ?>" class="see-all">See all &ldquo;<?php echo $kbe_child_term_name; ?>&rdquo; questions &rarr;</a>
                                        </li>
                            <?php
                                else :
                                    echo "No posts";
                                endif;
                            ?>
                            </ul>
                        <?php
                            }
                        ?>
                        </div>
                    <?php
                        } else {
                    ?>

                        <ul class="kbe_article_list">
                    <?php
                        $kbe_tax_post_args = array(
                            'post_type' => KBE_POST_TYPE,
                            'posts_per_page' => KBE_ARTICLE_QTY,
                            'orderby' => 'menu_order',
                            'order' => 'ASC',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => KBE_POST_TAXONOMY,
                                    'field' => 'slug',
                                    'terms' => $kbe_term_slug
                                )
                            )
                        );

                        $kbe_tax_post_qry = new WP_Query($kbe_tax_post_args);

                        if($kbe_tax_post_qry->have_posts()) :
                            while($kbe_tax_post_qry->have_posts()) :
                                $kbe_tax_post_qry->the_post();
                    ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </li>
                    <?php
                            endwhile; ?>
                                <li>
                                    <a href="<?php echo get_term_link($kbe_term_slug, 'kbe_taxonomy') ?>" class="see-all">See all &ldquo;<?php echo $kbe_term_name; ?>&rdquo; questions &rarr;</a>
                                </li>
                    <?php
                        else :
                            echo "No posts";
                        endif;
                    ?>
                        </ul>
                    <?php } ?>
                    </div>
            <?php
                } // end check for parent == 0
            } // end foreach
        ?>
            </div>
        </div>
        <!--/leftcol-->

        <p>Not finding your question answered? <a href="http://orawellness.pressedsolutions.com/contact/">Contact us here</a>.</p>
    </div>
    <!--content-->

    <!--aside-->
    <aside class="sidebar sidebar-primary widget-area" role="complementary" aria-label="Membership Pages Sidebar" itemscope itemtype="http://schema.org/WPSideBar" id="genesis-sidebar-primary">
    <?php genesis_widget_area( 'kbe_cat_widget' ); ?>
    </aside>
    <!--/aside-->

</div>
<?php get_footer('knowledgebase'); ?>
