<?php
function replaceTexts($dir)
{
    if (!is_dir($dir))
        return;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..')
            continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            replaceTexts($path);
        } else if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            $newContent = str_replace(
                ['e-Regulasi', 'e-Bimtek', 'e-Pj Kades'],
                ['Draft Regulasi', 'Pembinaan', 'SK Kades'],
                $content
            );
            if ($newContent !== $content) {
                file_put_contents($path, $newContent);
                echo "Updated: $path\n";
            }
        }
    }
}

replaceTexts(__DIR__ . '/resources/views');
echo "Done.";
