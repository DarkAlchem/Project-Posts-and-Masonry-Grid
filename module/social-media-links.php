<?php if( have_rows('social_media') ): ?>
    <h4>Social Media</h4>
    <div>
        <ul>
        <?php while( have_rows('social_media') ): the_row(); 
            $image_svg='ico-facebook.svg';
            $image_type = get_sub_field('social_type'); 
            $social_url = get_sub_field('social_url'); 
            switch($image_type){
                case 'youtube';
                    $image_svg='ico-youtube.svg';
                break;
                case 'twitter';
                    $image_svg='ico-twitter.svg';
                break;
                case 'instagram';
                    $image_svg='ico-instagram.svg';
                break;
            }?>

            <li>
                <a href='<?= $social_url; ?>' target='_self'>
                    <img src='<?= plugin_dir_url( __DIR__ ) . "svg/" . $image_svg; ?>'/>
                <a>
            </li>
        <?php endwhile; ?>
        </ul>
    </div>
<?php endif; ?>