<?php
    class Controller
    {
        var $vars = [];
        var $layout = "default";

        function set($d)
        {
            $this->vars = array_merge($this->vars, $d);
        }

        function render($filename, $contentOnly = false)
        {
            $view = ROOT . "Views/" . ucfirst(str_replace('Controller', '', get_class($this))) . '/' . $filename . '.php';
            if (file_exists($view))
            { // Renders the view with the data if the file exist
                extract($this->vars);
                ob_start();
                require_once($view);
                $content_for_layout = ob_get_clean();

                if ($contentOnly) {
                    //echo json_encode($content_for_layout);
                    echo $content_for_layout;
                } else {
                    require_once(ROOT . "Views/Layouts/" . $this->layout . '.php');
                }
            }
            else
            {
                require_once(ROOT . 'Views/404.php');
            }
        }

        public function isUserPageOwner($user)
        { // Check is the current user page is owned by this user
          // and thus has the right to access moderating tools
            $valid = false;
            if (CONNECTED) {
                $valid = !empty($user) && $_SESSION['userId'] === $user->getId();
                $valid = !empty($user) && $_SESSION['userContentId'] === $user->getContentId();
            }
            return $valid;
        }

        public function isUserOwner($id)
        { // Check if the id correspond to the connected user one
            $valid = false;
            if (CONNECTED) {
                $valid = $_SESSION['userId'] === $id;
            }
            return $valid;
        }

        protected function secure_form($form)
        { // Secure array of string values
            foreach ($form as $key => $value)
            {
                $form[$key] = $this->secure_input($value);
            }
            return $form;
        }

        protected function secure_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8', true);
            return $data;
        }

        protected function urlValidId($str)
        {
            return preg_replace("/[^A-Za-z0-9]/", "_", $str);
        }

        /**
         * @param  String $str The input string
         * @return String      The string without accents
         */
        function removeAccents($str)
        {
            $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
                'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã',
                'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
                'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ',
                'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę',
                'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī',
                'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ',
                'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ',
                'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť',
                'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ',
                'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ',
                'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');

            $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O',
                'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c',
                'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
                'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D',
                'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
                'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K',
                'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
                'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S',
                's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W',
                'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i',
                'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
            return str_replace($a, $b, $str);
        }

        /**
         * @param  String $str The input string
         * @return String      The URL-friendly string (lower-cased, accent-stripped,
         *                     spaces to dashes).
         */
        function toURLFriendly($str)
        {
            $str = removeAccents($str);
            $str = preg_replace(array('/[^a-zA-Z0-9 \'-]/', '/[ -\']+/', '/^-|-$/'), array('', '-', ''), $str);
            $str = preg_replace('/-inc$/i', '', $str);
            return strtolower($str);
        }

        /**
        * @package       BBCode Parser
        * @author        ToTamir
        * @copyright     2018 ToTamir
        * @license       MIT https://github.com/ToTamir/BBCode-Parser/blob/master/LICENSE
        * @version       1.0.2
        * @link          https://github.com/ToTamir/BBCode-Parser
        */

        /**
        * @see           Function       ParseBBCode()  Convert BBCode to HTML and returns parsed text as string
        * @param         String         $string        The string to parse
        * @return        String                        The parsed string
        */

        function ParseBBCode(string $string): string
        {
            $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8', true);
            $string = str_replace(["\r\n", "\n"], "\r", $string);
            $string = preg_replace_callback(['/\[(ol)\](.*?)\[\/ol\]/', '/\[(ul)\](.*?)\[\/ul\]/', '/\[(table)\](.*?)\[\/table\]/'], function($matches)
            {
                return '<'.$matches[1].'>'.preg_replace('/\s+/', ' ',$matches[2]).'</'.$matches[1].'>';
            }, $string);

            $patterns =
            [
                '/\[b\](.*?)\[\/b\]/',
                '/\[i\](.*?)\[\/i\]/',
                '/\[u\](.*?)\[\/u\]/',
                '/\[s\](.*?)\[\/s\]/',
                '/\[j\](.*?)\[\/j\]/',
                '/\[font=(.*?)\](.*?)\[\/font\]/',
                '/\[size=50\](.*?)\[\/size\]/',
                '/\[size=85\](.*?)\[\/size\]/',
                '/\[size=100\](.*?)\[\/size\]/',
                '/\[size=150\](.*?)\[\/size\]/',
                '/\[size=200\](.*?)\[\/size\]/',
                '/\[color=(([a-z]+)|(#[0-f]{6}?)|(rgb\(\d{1,3}?\,\d{1,3}?\,\d{1,3}?\)))\](.*?)\[\/color\]/',
                '/\[center\](.*?)\[\/center\]/',
                '/\[left\](.*?)\[\/left\]/',
                '/\[right\](.*?)\[\/right\]/',
                '/\[quote=(.*?)\](.*?)\[\/quote\]/',
                '/\[quote\](.*?)\[\/quote\]/',
                '/\[url=(.*?)\](.*?)\[\/url\]/',
                '/\[img=(.*?) alt=(.*?)\]/',
                '/\[img\](.*?)\[\/img\]/',
                '/\[youtube=(.*?)\]/',
                '/\[list\](.*?)\[\/list\]/',
                '/\[list=1\](.*?)\[\/list\]/',
                '/\[\*\](.*?)\[\/\*\]/',
                '/\[td\](.*?)\[\/td\]/',
                '/\[tr\](.*?)\[\/tr\]/',
                '/\[th\](.*?)\[\/th\]/',
                '/\[code\](.*?)\[\/code\]/',
                '/\r/'
            ];

            $replacements =
            [
                '<strong>$1</strong>',
                '<em>$1</em>',
                '<span style="text-decoration:underline;">$1</span>',
                '<del>$1</del>',
                '<span style="text-align:justify;text-justify:inter-word;">$1</span>',
                '<span style="font-family:$1;">$2</span>',
                '<font size="1">$1</font>',
                '<font size="2">$1</font>',
                '<font size="3">$1</font>',
                '<font size="4">$1</font>',
                '<font size="6">$1</font>',
                //'<span style="font-size:$1px;">$2</span>',
                '<span style="color:$1;">$5</span>',
                '<div style="text-align:center;">$1</div>',
                '<div style="text-align:left;">$1</div>',
                '<div style="text-align:right;">$1</div>',
                '<blockquote><strong>$1:</strong><br>$2</blockquote>',
                '<blockquote>$1</blockquote>',
                '<a href="$1">$2</a>',
                '<img class="img-fluid" src="$1" alt="$2">',
                '<img class="img-fluid" src="$1">',
                '<iframe src="https://www.youtube-nocookie.com/embed/$1" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
                '<ul>$1</ul>',
                '<ol>$1</ol>',
                '<li>$1</li>',
                '<td>$1</td>',
                '<tr>$1</tr>',
                '<th>$1</th>',
                '<pre><code>$1</code></pre>',
                '<br>'
            ];

            return preg_replace($patterns, $replacements, $string);
        }

    }