<?php

if($config->is->post) {

    $parent = Request::get('reply', false);

    function do_response_reply($response) {
        global $speak;
        $prefix = File::B(File::D($response->path));
        echo (Weapon::exist($prefix . '_footer') && Guardian::happy() ? ' / ' : "") . '<a class="a-reply" data-parent="' . $response->id . '" href="' . str_replace('&', '&amp;', HTTP::query('reply', $response->id)) . '#' . $prefix . '-form" title="' . Config::speak($prefix . '_reply_to_', Text::parse($response->name_raw, '->text')) . '">' . $speak->reply . '</a>';
    }

    function do_response_reply_css() {
        echo Asset::stylesheet(File::D(__DIR__) . DS . 'assets' . DS . 'shell' . DS . 'do.css');
    }

    function do_response_reply_js() {
        echo Asset::javascript(File::D(__DIR__) . DS . 'assets' . DS . 'sword' . DS . 'do.js');
    }

    function do_response_reply_x($post) {
        global $config, $speak, $parent;
        $s = Asset::loaded($config->protocol . ICON_LIBRARY_PATH) ? '<i class="fa fa-times-circle"></i> ' : "";
        echo '&#32;<a href="' . $post->url . '" class="btn btn-reject btn-reject-reply"' . ($parent === false ? ' style="display:none;"' : "") . '>' . $s . $speak->cancel . '</a>';
    }

    Weapon::add('comment_footer', 'do_response_reply', 20.1);
    Weapon::add('comment_form_button_after', 'do_response_reply_x');
    Weapon::add('shell_before', 'do_response_reply_css');
    Weapon::add('SHIPMENT_REGION_BOTTOM', 'do_response_reply_js');

    // No JavaScript
    if($parent !== false) {
        Guardian::memorize('parent', $parent);
        if($response = Get::commentAnchor($parent)) {
            Weapon::add('chunk_before', function($G) use($speak, $parent, $response) {
                if(File::N($G['data']['path']) === 'comment.form') {
                    $prefix = File::B(File::D($response->path));
                    $to = Config::speak($prefix . '_reply_to_', Cell::a('#' . $prefix . '-' . $parent, $response->name));
                    echo Cell::h4($to);
                }
            });
        }
    }

    // Error
    if($s = Request::post('parent', false)) {
        Filter::add('guardian:kick', function($url) use($config, $s) {
            if( ! Notify::errors()) return $url;
            $ss = explode('#', $url, 2);
            return $config->url_current . HTTP::query('reply', $s) . '#' . $ss[1];
        });
    }

}

function do_response_ping($message, $results = array()) {
    $results = (object) $results;
    if(isset($results->parent) && ! is_null($results->parent)) {
        $prefix = File::B(File::D($results->path));
        $name = is_callable('Get::' . $prefix . 'Anchor') ? call_user_func('Get::' . $prefix . 'Anchor', $results->parent)->name : $results->parent;
        $to = '<a href="#' . $prefix . '-' . $results->parent . '">@' . $name . '</a>';
        return strpos($message, '<p>') === 0 ? str_replace('^<p>', '<p>' . $to . ' ', '^' . $message) : $message . '<p>' . $to . '</p>';
    }
    return $message;
}

Filter::add('comment:message', 'do_response_ping');