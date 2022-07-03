<?php get_header(); 
    $image_landing=get_field('landing_image');
    $text_holder=get_field('photo_credits');
    $text_color=get_field('credits_color');
    $post_location=get_field('post_location');
    $website_url=get_field('website_url');
    //Article
    $post_tagline=get_field('post_tagline');
    $post_desc=get_field('description');
    //Stats
    $post_logo=get_field('post_logo');
    $year_founded=get_field('year_founded');
    $budget_size=get_field('budget_side');
    $staff_size=get_field('staff_size');
    $project_director=get_field('project_director');
    //Article Image
    $image_ad=get_field('ad_image');
    $ad_tagline='';
    $ad_url='';
    if (get_field('ad_link')){
        $ad_tagline=get_field('ad_link')['title'];
        $ad_url=get_field('ad_link')['url'];
    }
    //Contact
    $contact_name=get_field('contact_name');
    $contact_title=get_field('contact_title');
    $contact_email=get_field('contact_email');

    $query=new WP_Query( array ( 
        'orderby' => 'rand', 
        'post__not_in' => array(get_the_id()),
        'post_type' => 'project-post',
        'posts_per_page' => '3' 
    ) );
?>

<div class='ip_container'>
    <div class='ip_row'>
        <div class='ip_landing'>
            <img src='<?= $image_landing; ?>'>
            <div class="textholder">
                <h4 style='color:<?= $text_color; ?>'><?= $text_holder; ?></h4>
            </div>
        </div>
    </div>
    <div class='ip_row'>
        <div class="ip_article">
            <div class='ip_posttitle'>
                <h1><?= the_title(); ?></h1>
            </div>
            <div class='ip_info'>
                <h4><img src="<?= plugin_dir_url( __FILE__ ) . 'svg/ico-location-profile.svg'; ?>"><?= $post_location; ?></h4>
                <a href='<?= $website_url; ?>' target='_blank'>
                    <h4><img src="<?= plugin_dir_url( __FILE__ ) . 'svg/ico-globe.svg'; ?>"><?= $website_url; ?></h4>
                </a>
            </div>
            <div class='ip_desc'>
                <p class='tagline'><?= $post_tagline; ?></p>
                <?= $post_desc; ?>
            </div>
        </div>
        <div class='ip_sidebar'>
            <div class='ip_stats'>
                <img src='<?= $post_logo['url']; ?>'>
                <?php if($year_founded!=''): ?>
                <h4>Year Founded: <span><?= $year_founded; ?></span></h4>
                <?php endif; ?>
                <?php if($budget_size!=''): ?>
                <div class='ip_bar'></div>
                <h4>Budget Size: <span><?= $budget_size; ?></span></h4>
                <?php endif; ?>
                <?php if($staff_size!=''): ?>
                <div class='ip_bar'></div>
                <h4>Staff Size: <span><?= $staff_size; ?></span></h4>
                <?php endif; ?>
                <?php if($project_director!=''): ?>
                    <div class='ip_bar'></div>
                    <h4><?= $project_director; ?></h4>
                <?php endif; ?>
            </div>
            <div class='ip_social-media'>
                <?php require 'module/social-media-links.php'; ?>
            </div>
            <?php if($ad_url!='' && $image_ad!=null):?>
            <a href='<?= $ad_url; ?>' target='_blank'>
                <div class='ip_project-ad'>
                    <img src='<?= $image_ad['url']; ?>'>
                    <h4><?= $ad_tagline; ?></h4>
                    <h4 class='ip_link'><?= substr($ad_url,7); ?></h4>
                    <h4>Learn More here →</h4>
                </div>
            </a>
            <?php endif; ?>
            <div class='ip_contact'>
                <h4 class='ip_header'>Contact Info</h4>
                <h4 class='ip_name'><?= $contact_name; ?></h4>
                <h4 class='ip_title'><?= $contact_title; ?></h4>
                <a href='mailto:<?= $contact_email; ?>' target='_blank'>
                    <h4 class='ip_email'><?= $contact_email; ?></h4>
                </a>
            </div>
        </div>
    </div>
    <div class='ip_row project-head'>
        <h2>Similar</h2>
    </div>
    <div class='ip_row project-queue'>
        <?php while ( $query->have_posts() ) : $query->the_post(); 
				    	$image=get_field('landing_image');
				    	$location=get_field('post_location');?>
            <div class='ip_project-cont'>
                <a href='<?= get_permalink(); ?>' target='_self'>
                    <div class='ip_project'>
                        <div class='ip_image'>
                            <img src='<?= $image; ?>'>
                        </div>
                        <div class='ip_desc'>
                            <h4><?= the_title(); ?></h4>
                            <div class="bar"></div>
                            <p><img src="<?= plugin_dir_url( __FILE__ ) . 'svg/ico-location.svg'; ?>"><?= $location; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endwhile;
        wp_reset_postdata();?>
    </div>
    <div class='ip_row'>
        <?php the_content(); ?>
    </div>
</div>
<div class="ip_containerB"></div>
	<div class='ip_row'>
        <?= do_shortcode('[templatera id="4403"]'); ?>
    </div>
</div>

<?php get_footer(); ?>