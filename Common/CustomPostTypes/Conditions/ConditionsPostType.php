<?php

namespace WMIP2C\Common\CustomPostTypes\Conditions;

use WMIP2C\Common\Enums\WordpressActions;
use WMIP2C\Common\Services\Singleton;
use WP_REST_Request;

/**
 * @method static ConditionsPostType instance;
 */
final class ConditionsPostType extends Singleton
{
    public const POST_TYPE_NAME = 'conditions_content';
    public const REST_ROUTE = '/conditions/contents';
    public const DUPLICATE_ACTION = WMIP2C_PLUGIN_PREFIX . '_' . self::POST_TYPE_NAME . '_duplicate';

    public function register(): void
    {
        add_action(WordpressActions::WP_LOADED, function () {
            register_post_type(self::POST_TYPE_NAME, $this->arguments());
        });

        add_action(WordpressActions::ADMIN_INIT, function () {
            $admins = get_role('administrator');

            $admins->add_cap(ConditionsContentsPostTypeCapabilities::EDIT_CONTENT);
            $admins->add_cap(ConditionsContentsPostTypeCapabilities::EDIT_CONTENTS);
            $admins->add_cap(ConditionsContentsPostTypeCapabilities::EDIT_OTHER_CONTENTS);
            $admins->add_cap(ConditionsContentsPostTypeCapabilities::PUBLISH_CONTENTS);
            $admins->add_cap(ConditionsContentsPostTypeCapabilities::READ_CONTENT);
            $admins->add_cap(ConditionsContentsPostTypeCapabilities::READ_PRIVATE_CONTENTS);
            $admins->add_cap(ConditionsContentsPostTypeCapabilities::DELETE_CONTENT);
            $admins->add_cap(ConditionsContentsPostTypeCapabilities::DELETE_CONTENTS);
        });

        add_action('admin_action_' . self::DUPLICATE_ACTION, [$this, 'duplicatePost']);
        add_filter('post_row_actions', [$this, 'duplicatePostLink'], 10, 2);
        add_filter('rest_' . self::POST_TYPE_NAME . '_query', [$this, 'editRestPostsPerPageToMax'], 2, 10);
    }

    public function arguments(): array
    {
        return [
            'label'               => __('Conditions Content', 'text_domain'),
            'description'         => __('Content used for conditions', 'text_domain'),
            'labels'              => [
                'name'                  => _x('Conditions Content', 'Conditions Content General', 'text_domain'),
                'singular_name'         => _x('Post Type', 'Post Type Singular Name', 'text_domain'),
                'menu_name'             => __('Conditions Content', 'text_domain'),
                'name_admin_bar'        => __('Post Type', 'text_domain'),
                'archives'              => __('Item Archives', 'text_domain'),
                'attributes'            => __('Item Attributes', 'text_domain'),
                'parent_item_colon'     => __('Parent Item:', 'text_domain'),
                'all_items'             => __('All Items', 'text_domain'),
                'add_new_item'          => __('Add New Item', 'text_domain'),
                'add_new'               => __('Add New', 'text_domain'),
                'new_item'              => __('New Item', 'text_domain'),
                'edit_item'             => __('Edit Item', 'text_domain'),
                'update_item'           => __('Update Item', 'text_domain'),
                'view_item'             => __('View Item', 'text_domain'),
                'view_items'            => __('View Items', 'text_domain'),
                'search_items'          => __('Search Item', 'text_domain'),
                'not_found'             => __('Not found', 'text_domain'),
                'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
                'featured_image'        => __('Featured Image', 'text_domain'),
                'set_featured_image'    => __('Set featured image', 'text_domain'),
                'remove_featured_image' => __('Remove featured image', 'text_domain'),
                'use_featured_image'    => __('Use as featured image', 'text_domain'),
                'insert_into_item'      => __('Insert into item', 'text_domain'),
                'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
                'items_list'            => __('Items list', 'text_domain'),
                'items_list_navigation' => __('Items list navigation', 'text_domain'),
                'filter_items_list'     => __('Filter items list', 'text_domain'),
            ],
            'capabilities'        => [
                'edit_post'          => ConditionsContentsPostTypeCapabilities::EDIT_CONTENT,
                'edit_posts'         => ConditionsContentsPostTypeCapabilities::EDIT_CONTENTS,
                'edit_others_posts'  => ConditionsContentsPostTypeCapabilities::EDIT_OTHER_CONTENTS,
                'publish_posts'      => ConditionsContentsPostTypeCapabilities::PUBLISH_CONTENTS,
                'read_post'          => ConditionsContentsPostTypeCapabilities::READ_CONTENT,
                'read_private_posts' => ConditionsContentsPostTypeCapabilities::READ_PRIVATE_CONTENTS,
                'delete_post'        => ConditionsContentsPostTypeCapabilities::DELETE_CONTENT,
                'delete_posts'       => ConditionsContentsPostTypeCapabilities::DELETE_CONTENTS,
            ],
            'supports'            => ['title', 'editor'],
            'show_in_rest'        => true,
            'rest_base'           => 'conditions/contents',
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
        ];
    }

    public function duplicatePost(): void
    {
        global $wpdb;
        if (!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action']))) {
            wp_die('No post to duplicate has been supplied!');
        }
        /*
         * Nonce verification
         */
        if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__))) {
            return;
        }

        $post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
        $post = get_post($post_id);
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        if (!isset($post)) {
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }

        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title . ' #duplicate',
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        $new_post_id = wp_insert_post($args);

        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos) != 0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value)";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                if ($meta_key == '_wp_old_slug') {
                    continue;
                }
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query .= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }

        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    }

    public function duplicatePostLink($actions, $post)
    {
        if (!isset($_GET['post_type']) || $_GET['post_type'] !== self::POST_TYPE_NAME) {
            return $actions;
        }

        $duplicateActionName = self::DUPLICATE_ACTION;
        $href = "admin.php?action={$duplicateActionName}&post={$post->ID}";
        $nonceHref = wp_nonce_url($href, basename(__FILE__), 'duplicate_nonce');

        $actions['duplicate'] = '<a href="' . $nonceHref . '" title="Duplicate this item" rel="permalink">Duplicate</a>';

        return $actions;
    }

    public function editRestPostsPerPageToMax(array $args, WP_REST_Request $request): array
    {
        $post_per_page = $request->get_param(WMIP2C_PLUGIN_PREFIX . '_per_page') ?? 10;
        $args['posts_per_page'] = $post_per_page;

        return $args;
    }
}
