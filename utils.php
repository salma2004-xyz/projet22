<?php
// Function to clean text input
function nettoyerTexte($texte) {
    $texte = trim($texte);
    $texte = stripslashes($texte);
    $texte = htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
    return $texte;
}

// Function to convert tags array to string
function tagsToString($tags) {
    $tags = array_map('trim', $tags);
    $tags = array_filter($tags);
    return implode(',', $tags);
}
?>
