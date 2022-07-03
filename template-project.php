<?php /* Template Name: Project Masonry Page */ 
    $query=new WP_Query( array ( 
        'orderby' => 'title', 
        'order' => 'ASC',
        'post_type' => 'project-post',
        'posts_per_page' => '-1' 
    ) );

    $post_list=array();
    $post_name=array();
    $post_location=array();
    $post_type=array();
    while ( $query->have_posts() ) : $query->the_post(); 
		$post_list[]=get_the_id();
        $post_name[get_the_id()]=get_the_title();
        foreach(get_the_category() as $category) { 
            $post_type[]=$category->cat_name; 
        } 
        $post_location[]=ucwords(get_field('post_location'));
    endwhile;
    wp_reset_postdata();
    $post_name=array_unique($post_name,SORT_STRING);
    $post_type=array_unique($post_type,SORT_STRING);
    $post_location=array_unique($post_location,SORT_STRING);

get_header(); ?>

<div class='ip_container'>
    <div class='ip_row bg-blue'>
        <div class='search_fields'>
            <select data-type='project-name'>
                <option selected disabled>-- Name --</option>
                <option value='all'>All</option>
                <?php foreach ($post_name as $key=>$name) { ?>
                    <option value='<?= $key; ?>'><?= $name; ?></option>
                <?php }?>
            </select>
            <select data-type='project-type'>
                <option selected disabled>-- Discipline --</option>
                <option value='all'>All</option>
                <?php foreach ($post_type as $type) { ?>
                    <option value='<?= $type; ?>'><?= $type; ?></option>
                <?php }?>
            </select>
            <select data-type='project-location'>
                <option selected disabled>-- Location --</option>
                <option value='all'>All</option>
                <?php foreach ($post_location as $location) { ?>
                    <option value='<?= $location; ?>'><?= $location; ?></option>
                <?php }?>
            </select>
            <form id="searchform" action="<?php echo home_url( '/' ); ?>" method="get">
                <input class="inlineSearch" type="text" name="s" value="Enter a keyword" onblur="if (this.value == '') {this.value = 'Enter a keyword';}" onfocus="if (this.value == 'Enter a keyword') {this.value = '';}" />
                <input type="hidden" name="post_type" value="project-post" />
            </form>
        </div>
    </div>
    <div class='ip_row'>
        <div class='project_masonry _extend'>
            <?php foreach ($post_list as $project_id) { 
                $image=get_field('landing_image',$project_id); 
                $location=get_field('post_location',$project_id);
                $cat_arr=array();
                $cat_str='';
                foreach(get_the_category($project_id) as $category) { 
                    $cat_arr[]=$category->cat_name; 
                } 
                $cat_str=implode(' ',$cat_arr); ?>
                <div class='ip_project-cont'>
                    <a data-id='<?= $project_id; ?>' data-type='<?= $cat_str; ?>' data-location='<?= ucwords($location); ?>' href='<?= get_permalink($project_id); ?>' target='_self'>
                        <div class='ip_project'>
                            <div class='bgholder'>
                                <img class='lazy _lazy' data-src='<?= $image; ?>'>
                            </div>
                            <div class='overlay'></div>
                            <div class='textholder'>
                                <div>
                                    <h4><?= get_the_title($project_id); ?></h4>
                                    <p><?= $location; ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php }?>
        </div>
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