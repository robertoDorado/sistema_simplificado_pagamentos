<?php

function executeMigrations(string $instance)
{
    echo "------------ CLASSE: " . $instance . " -----------------\n";
    $object = new $instance();
    $methods = array_reverse(get_class_methods($object));
    
    foreach ($methods as $method) {
        if ($method != "__construct") {
            echo "EXECUTANDO: " . $method . "\n";
            $object->$method();
        }
    }
    echo "----------------------------------------------\n";
}

function transformCamelCaseToSnakeCase(array $args)
{
    foreach($args as &$originalString) {
        $transformedString = preg_replace('/([a-z])([A-Z])/', '$1_$2', $originalString);
        $originalString = strtolower($transformedString);
    }
    return $args;
}

/**
 * @param string $path
 * @return string
 */
function url(string $path = null): string
{
    if (str_replace("www.", "", $_SERVER['HTTP_HOST']) == "localhost") {
        if ($path) {
            return CONF_URL_TEST .
                "/" .
                ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }

    if ($path) {
        return CONF_URL_BASE .
            "/" .
            ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

/**
 * @param string|null $path
 * @param string $theme
 * @return string
 */
function theme(string $path = null, string $theme = CONF_VIEW_THEME): string
{
    if (str_replace("www.", "", $_SERVER['HTTP_HOST']) == "localhost") {
        if ($path) {
            return CONF_URL_TEST . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST . "/themes/{$theme}";
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }
    return CONF_URL_BASE . "/themes/{$theme}";
}

/**
 * @param string $url
 * @return void
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit();
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit();
    }
}

/**
 * filter_type: Principais campos de consulta para os relatórios. O que não estiver
 * nessa lista será tratado como FILTER_SANITIZE_STRIPPED pelos helpers de filtro
 * @return null|array
 */
function filter_type(): array
{
    $filterFields = [
        "route" => FILTER_SANITIZE_STRIPPED,
        "product" => FILTER_SANITIZE_NUMBER_INT,
        "product_id" => FILTER_SANITIZE_NUMBER_INT,
        "country" => FILTER_SANITIZE_STRIPPED,
        "device" => FILTER_SANITIZE_NUMBER_INT,
        "redirect" => FILTER_SANITIZE_ENCODED,
        "status" => FILTER_SANITIZE_NUMBER_INT,
        "upsell" => FILTER_SANITIZE_NUMBER_INT,
        "paymentMethod" => FILTER_SANITIZE_NUMBER_INT,
        "company" => FILTER_SANITIZE_NUMBER_INT,
        "affiliate" => FILTER_SANITIZE_NUMBER_INT,
        "level" => FILTER_SANITIZE_NUMBER_INT,
        "group_id" => FILTER_SANITIZE_NUMBER_INT,
        "report_id" => FILTER_SANITIZE_NUMBER_INT,
    ];
    return $filterFields;
}

/**
 * filter_array: Filtrar campos de array ou globais GET e POST
 * @param array $array
 * @return array
 */
function filter_array(array $array): array
{
    $filterFields = filter_type();

    foreach ($array as $key => $value) {
        if (in_array($key, array_keys($filterFields))) {
            $filterArr[$key] = $filterFields[$key];
        } else {
            $filterArr[$key] = FILTER_SANITIZE_STRIPPED;
        }
    }
    return filter_var_array($array, $filterArr);
}

/**
 * @param string $string
 * @param string $type = int, string, chars, etc
 * @return string
 */
function filter_variable(string $string, $type = null): string
{
    if (!empty($type)) {
        $type = mb_convert_case($type, MB_CASE_LOWER);

        if ($type == 'default') {
            return filter_var($string, FILTER_DEFAULT);
        } elseif ($type == 'int') {
            return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
        } elseif ($type == 'string') {
            return filter_var($string, FILTER_SANITIZE_STRING);
        } elseif ($type == 'chars') {
            return filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
        } elseif ($type == 'mail' || $type == 'email') {
            return filter_var($string, FILTER_VALIDATE_EMAIL);
        }
    }
    return filter_var($string, FILTER_SANITIZE_STRIPPED);
}
