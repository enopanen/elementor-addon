<?php
/**
 * Elementor Popular Posts Widget
 */
class Popular_Posts_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'popular-posts';
    }
    public function get_title() {
        return __('Popular Posts', 'popular-posts');
    }
    public function get_icon() {
        return 'eicon-post-list';
    }
    public function get_categories() {
        return ['general'];
    }
    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'popular-posts'),
            ]
        );
        $this->add_control(
            'num_posts',
            [
                'label' => __('Number of Posts', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
            ]
        );
        $this->add_control(
            'show_thumbnail',
            [
                'label' => __('Show Thumbnail', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'popular-posts'),
                'label_off' => __('Hide', 'popular-posts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'thumbnail_size',
            [
                'label' => __('Thumbnail Size', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'thumbnail',
                'options' => [
                    'thumbnail' => __('Thumbnail', 'popular-posts'),
                    'medium' => __('Medium', 'popular-posts'),
                    'large' => __('Large', 'popular-posts'),
                    'full' => __('Full', 'popular-posts'),
                ],
                'condition' => [
                    'show_thumbnail' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'show_date',
            [
                'label' => __('Show Date', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'popular-posts'),
                'label_off' => __('Hide', 'popular-posts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'popular-posts'),
                'label_off' => __('Hide', 'popular-posts'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Length', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 15,
                'min' => 1,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'popular-posts'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .popular-posts-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .popular-posts-title',
            ]
        );
        $this->add_control(
            'date_color',
            [
                'label' => __('Date Color', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .popular-posts-date' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'selector' => '{{WRAPPER}} .popular-posts-date',
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'excerpt_color',
            [
                'label' => __('Excerpt Color', 'popular-posts'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .popular-posts-excerpt' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .popular-posts-excerpt',
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        $num_posts = $settings['num_posts'];
        $show_thumbnail = $settings['show_thumbnail'];
        $thumbnail_size = $settings['thumbnail_size'];
        $show_date = $settings['show_date'];
        $show_excerpt = $settings['show_excerpt'];
        $excerpt_length = $settings['excerpt_length'];
        $popular_posts = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => $num_posts,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
        ));
        if ($popular_posts->have_posts()) {
            echo '<div class="popular-posts-widget">';
            while ($popular_posts->have_posts()) {
                $popular_posts->the_post();
                echo '<div class="popular-posts-item">';
                if ($show_thumbnail === 'yes') {
                    echo '<div class="popular-posts-thumbnail">';
                    echo get_the_post_thumbnail(get_the_ID(), $thumbnail_size);
                    echo '</div>';
                }
                echo '<div class="popular-posts-content">';
                echo '<h3 class="popular-posts-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
                if ($show_date === 'yes') {
                    echo '<div class="popular-posts-date">' . get_the_date() . '</div>';
                }
                if ($show_excerpt === 'yes') {
                    echo '<div class="popular-posts-excerpt">' . wp_trim_words(get_the_excerpt(), $excerpt_length) . '</div>';
                }
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo 'No popular posts found.';
        }
    }
}