<?php   class Model_inform extends Model
{
    public function nameinclude()
    {
        return "Информация о системе";
    }

    public function grafik_include()
    {
        $arriv = array();
        $arr = $this->db->query('SELECT * FROM graph');

        foreach ($arr as $row) {
            $table = $row['tables'];
            $names = $row['names'];
            $graph = $row['graph'];
            $where = htmlspecialchars_decode($row['wheres'], ENT_QUOTES);
            $pole = $row['fields'];
            $color = $row['color'];
            $kod = $row['kod'];
            $func = $row['funcs'];
            $where = str_replace('и', 'AND', $where);
            $where = str_replace('И', 'AND', $where);
            $where = str_replace('или', 'OR', $where);
            $where = str_replace('ИЛИ', 'OR', $where);
            if ($where != '0') {

                $arr2 = $this->db->query("SELECT * FROM " . $table . " WHERE " . $where . "");
            } else {

                $arr2 = $this->db->query('SELECT * FROM ' . $table);
            }

            $count = 0;
            if (empty(${$pole})) {
                ${$pole} = 0;
            }
            foreach ($arr2 as $row2) {

                if ($func == 'count') {

                    ${$pole} += 1;
                } else {

                    ${$pole} += $row2[$pole];
                }

                $count += 1;

            }

            if ($count > 0) {

                $value = ${$pole} * 100 / $count;
                $arriv[$kod]['color'][] = $color;
                $arriv[$kod]['pole'][] = $value;
                $arriv[$kod]['value'][] = $names;
                $arriv[$kod]['graph'] = $graph;
                ${$pole} = 0;
            }
        }
        return $arriv;
    }

    public function Model_chmod(){
         return [1,2];

    }


}