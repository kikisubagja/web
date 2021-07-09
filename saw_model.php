// I have 6 kritera and 3 Alternatif
// Thanks for I


public function hitungSAW() {
        $alternatif = $this->db->get('tbl_user');
        $kriteria = $this->getAllKriteria();
        // hitung nilai normalisasi
        foreach ($alternatif->result_array() as $a) {
            foreach ($kriteria->result_array() as $k) {
                if ($k['atribut'] == 'cost') {
                    $min = $this->db->select_min('nilai')->get_where('tbl_nilai', ['n_kriteria' => $k['id_kriteria']])->row_array();
                    $nilaiKriteria = $this->db->get_where('tbl_nilai', ['n_alternatif' => $a['id_user'], 'n_kriteria' => $k['id_kriteria']])->row_array();
                    $hasilNormalisasi[$a['id_user']][$k['id_kriteria']] = (float)$min['nilai'] / (float)$nilaiKriteria['nilai'];
                    // var_dump($hasilNormalisasi); die;
                }else{
                    $max = $this->db->select_max('nilai')->get_where('tbl_nilai', ['n_kriteria' => $k['id_kriteria']])->row_array();
                    $nilaiKriteria = $this->db->get_where('tbl_nilai', ['n_alternatif' => $a['id_user'], 'n_kriteria' => $k['id_kriteria']])->row_array();
                    $hasilNormalisasi[$a['id_user']][$k['id_kriteria']] = (float)$nilaiKriteria['nilai'] / (float)$max['nilai'];
                }
            }
        }
        $value['normalisasi'] = $hasilNormalisasi;
        // var_dump($value); die;

        // hitung nilai preferensi P
        foreach ($alternatif->result_array() as $alt) {
            $temp = 0;
            foreach ($kriteria->result_array() as $ktr) {
                $temp += ($hasilNormalisasi[$alt['id_user']][$ktr['id_kriteria']] * $ktr['weight']);
            }
            $nilaiPreferensiP[$alt['id_user']]['nilai'] = $temp;
            $nilaiPreferensiP[$alt['id_user']]['kode'] = $alt['id_user'];
        }
        $value['Preferensi P'] = $nilaiPreferensiP;
        // var_dump($value); die;
        return $value;
    }
