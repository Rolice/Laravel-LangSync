<?php namespace Rolice\LangSync;

use Config;
use File;

/**
 * Class Sync
 *
 * @package Rolice\LangSync
 */
class Sync
{
    const TabSize = 4;

    /**
     * Run synchronization
     *
     * @param LabelsCollection $collection
     */
    public function execute(LabelsCollection $collection)
    {

        foreach (Config::get('app.available_locales') as $language) {
            foreach ($collection->get() as $file => $labels) {
                if ($file == 'manual') {
                    continue;
                }

                $loc = new Localization($language, $file);
                $data = $loc->merge($labels);
                $this->save($language, $file, $data);
            }
        }
    }

    /**
     * Save merged data to file
     *
     * @param $language
     * @param $file
     * @param $data
     */
    public function save($language, $file, $data)
    {
        $filename = $this->file($language, $file);

        $output = null;
        $this->render($data, $output, 0);

        File::put($filename, $output);
    }

    /**
     * Get localization file
     *
     * @param $language
     * @param $file
     *
     * @return string
     */
    private function file($language, $file)
    {
        $dir = app_path('lang/' . $language);

        if (!File::exists($dir)) {
            File::makeDirectory($dir);
        }

        return $dir . '/' . $file . '.php';
    }

    /**
     * @param array  $data   Nested data about translations
     * @param string $result The resulting buffer holding new lang file contents (php code)
     * @param int    $level  Current recursion level
     */
    private function render(array $data, &$result = null, $level = 0)
    {
        $level = (int)$level;

        if (0 == $level) {
            $result = "<?php\n\nreturn [\n\n";
        }

        foreach ($data as $key => $label) {
            if (!is_scalar($label)) {
                $key = str_replace('\'', '\\\'', $key);

                $result .= str_repeat(' ', ($level + 1) * self::TabSize) . "'$key' => [\n\n";

                $this->render((array)$label, $result, $level + 1);

                $result .= "\n" . str_repeat(' ', ($level + 1) * self::TabSize) . "],\n\n";
                continue;
            }

            $key = str_replace('\'', '\\\'', $key);
            $label = str_replace('\'', '\\\'', $label);

            $result .= str_repeat(' ', ($level + 1) * self::TabSize) . "'$key' => '$label',\n";
        }

        if (0 == $level) {
            $result .= "\n];";
        }
    }
}