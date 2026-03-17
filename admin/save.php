<?php
require_once __DIR__ . '/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$content = load_content();

// Simple text sections
foreach (['hero', 'wat', 'cta', 'meta'] as $section) {
    if (isset($_POST[$section])) {
        $content[$section] = array_map('trim', $_POST[$section]);
    }
}

// Voordelen
if (isset($_POST['voordelen'])) {
    $content['voordelen']['title'] = trim($_POST['voordelen']['title'] ?? '');
    $content['voordelen']['subtitle'] = trim($_POST['voordelen']['subtitle'] ?? '');
    $content['voordelen']['items'] = [];
    if (!empty($_POST['voordelen']['items'])) {
        foreach ($_POST['voordelen']['items'] as $item) {
            if (!empty(trim($item['title'] ?? ''))) {
                $content['voordelen']['items'][] = [
                    'icon' => trim($item['icon'] ?? ''),
                    'title' => trim($item['title'] ?? ''),
                    'text' => trim($item['text'] ?? ''),
                ];
            }
        }
    }
}

// Vergelijking - convert text lines to arrays
if (isset($_POST['vergelijk'])) {
    $content['vergelijk']['title'] = trim($_POST['vergelijk']['title'] ?? '');
    $content['vergelijk']['subtitle'] = trim($_POST['vergelijk']['subtitle'] ?? '');

    $content['vergelijk']['zandstralen'] = array_values(array_filter(
        array_map('trim', explode("\n", $_POST['vergelijk']['zandstralen_text'] ?? ''))
    ));
    $content['vergelijk']['laserstralen'] = array_values(array_filter(
        array_map('trim', explode("\n", $_POST['vergelijk']['laserstralen_text'] ?? ''))
    ));
}

// Toepassingen
if (isset($_POST['toepassingen'])) {
    $content['toepassingen']['title'] = trim($_POST['toepassingen']['title'] ?? '');
    $content['toepassingen']['subtitle'] = trim($_POST['toepassingen']['subtitle'] ?? '');
    $content['toepassingen']['items'] = [];
    if (!empty($_POST['toepassingen']['items'])) {
        foreach ($_POST['toepassingen']['items'] as $item) {
            if (!empty(trim($item['title'] ?? ''))) {
                $content['toepassingen']['items'][] = [
                    'icon' => trim($item['icon'] ?? ''),
                    'title' => trim($item['title'] ?? ''),
                    'text' => trim($item['text'] ?? ''),
                ];
            }
        }
    }
}

// Werkwijze
if (isset($_POST['werkwijze'])) {
    $content['werkwijze']['title'] = trim($_POST['werkwijze']['title'] ?? '');
    $content['werkwijze']['subtitle'] = trim($_POST['werkwijze']['subtitle'] ?? '');
    $content['werkwijze']['stappen'] = [];
    if (!empty($_POST['werkwijze']['stappen'])) {
        foreach ($_POST['werkwijze']['stappen'] as $stap) {
            if (!empty(trim($stap['title'] ?? ''))) {
                $content['werkwijze']['stappen'][] = [
                    'title' => trim($stap['title'] ?? ''),
                    'text' => trim($stap['text'] ?? ''),
                ];
            }
        }
    }
}

// Contact
if (isset($_POST['contact'])) {
    $content['contact']['title'] = trim($_POST['contact']['title'] ?? '');
    $content['contact']['subtitle'] = trim($_POST['contact']['subtitle'] ?? '');
    $content['contact']['telefoon'] = trim($_POST['contact']['telefoon'] ?? '');
    $content['contact']['email'] = trim($_POST['contact']['email'] ?? '');
    $content['contact']['locatie'] = trim($_POST['contact']['locatie'] ?? '');
    $content['contact']['bereikbaar'] = trim($_POST['contact']['bereikbaar'] ?? '');
    $content['contact']['form_button'] = trim($_POST['contact']['form_button'] ?? '');

    $content['contact']['diensten'] = array_values(array_filter(
        array_map('trim', explode("\n", $_POST['contact']['diensten_text'] ?? ''))
    ));
}

save_content($content);
header('Location: index.php?saved=1');
exit;
