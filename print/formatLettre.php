<?php

// Function which returns number to words
function numberToWord($num = '')
{
    $num    = (string) ((int) $num);

    if ((int) ($num) && ctype_digit($num)) {
        $words  = array();

        $num    = str_replace(array(',', ' '), '', trim($num));

        $list1  = array(
            '', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept','huit', 'neuf', 
            'dix', 'onze', 'douze', 'treize', 'quatorze','quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf',
            'vingt','vingt-un','vingt-deux','vingt-trois','vingt-quatre','vingt-cinq','vingt-six','vingt-sept','vingt-huit','vingt-neuf',
            'trente','trente-un','trente-deux','trente-trois','trente-quatre','trente-cinq','trente-six','trente-sept','trente-huit','trente-neuf',
            'quarante','quarante-un','quarante-deux','quarante-trois','quarante-quatre','quarante-cinq','quarante-six','quarante-sept','quarante-huit','quarante-neuf',
            'cinquante','cinquante-un','cinquante-deux','cinquante-trois','cinquante-quatre','cinquante-cinq','cinquante-six','cinquante-sept','cinquante-huit','cinquante-neuf',
            'soixante','soixante-un','soixante-deux','soixante-trois','soixante-quatre','soixante-cinq','soixante-six','soixante-sept','soixante-huit','soixante-neuf',
            'soixante-dix','soixante-onze','soixante-douze','soixante-treize','soixante-quatorze','soixante-quinze','soixante-seize','soixante-dix-sept','soixante-dix-huit','soixante-dix-neuf',
            'quatre-vingts','quatre-vingt-un','quatre-vingt-deux','quatre-vingt-trois','quatre-vingt-quatre','quatre-vingt-cinq','quatre-vingt-six','quatre-vingt-sept','quatre-vingt-huit','quatre-vingt-neuf',
            'quatre-vingt-dix','quatre-vingt-onze','quatre-vingt-douze','quatre-vingt-treize','quatre-vingt-quatorze','quatre-vingt-quinze','quatre-vingt-seize','quatre-vingt-dix-sept','quatre-vingt-dix-huit','quatre-vingt-dix-neuf',
            'cent'
        );

        $list3  = array(
            '', 'mille', 'million', 'milliard', 'mille milliards'
        );

        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num    = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);

        foreach ($num_levels as $num_part) {
            $levels--;
            $hundreds   = (int) ($num_part / 100);
            $hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' cent' . ($hundreds == 1 ? '' : '') . ' ' : '');
            $tens       = (int) ($num_part % 100);
            $singles    = '';

            if ($tens < 100) {
                $tens = ($tens ? ' ' . $list1[$tens] . '' : '');
            }
            else{
                ($tens = (int) ($tens / 100));
                $tens = '' . $list3[$tens] . ' ';
                $singles = (int) ($num_part % 100);
                $singles = '' . $list1[$singles] . ' ';
            }
            
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels]. '' : '');
        }
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }

        $words  = implode(', ', $words);

        $words  = trim(str_replace(' ,', ',', ($words)), ', ');
        if ($commas) {
            $words  = str_replace(',', '', $words);
        }

        return $words;
    } else if (!((int) $num)) {
        return 'Zero';
    }
    return '';
}

// $word = numberToWord(120);
// echo "<h4>120: " . $word . "</h4>";

?>