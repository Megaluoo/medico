<?php

function get_specialty_sections(string $specialty): array
{
    $path = __DIR__ . '/specialties/' . $specialty . '.php';

    if (file_exists($path)) {
        return include $path;
    }

    return [];
}

function render_sections(array $sections, array $formData = []): void
{
    foreach ($sections as $index => $section) {
        $sectionId = 'section_' . $index;
        echo '<div class="section">';
        echo '<button class="section-header" type="button" data-target="#' . $sectionId . '">';
        echo '<span>' . htmlspecialchars($section['title']) . '</span>';
        echo '<span class="chevron">⌄</span>';
        echo '</button>';
        echo '<div id="' . $sectionId . '" class="section-body">';

        foreach ($section['fields'] as $name => $label) {
            $value = $formData[$section['key']][$name] ?? '';
            echo '<label>';
            echo '<span>' . htmlspecialchars($label) . '</span>';
            echo '<input type="text" name="form_data[' . htmlspecialchars($section['key']) . '][' . htmlspecialchars($name) . ']" value="' . htmlspecialchars($value) . '" />';
            echo '</label>';
        }

        echo '</div>';
        echo '</div>';
    }
}
