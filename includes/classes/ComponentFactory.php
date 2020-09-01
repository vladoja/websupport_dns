<?php
class ComponentFactory
{

    public static function create_dns_dropdown()
    {
        $html = "<div class='dropdown'>" . PHP_EOL;
        $html .= '<select name="dns_type" id="dns_type">' . PHP_EOL;
        foreach (DNS_TYPES as $type) {
            $html .= sprintf('<option value="%s">%s</option>', strtolower($type), $type);
        }
        $html .= '</select>' . PHP_EOL;
        $html .= "</div>" . PHP_EOL;

        return $html;
    }
}
