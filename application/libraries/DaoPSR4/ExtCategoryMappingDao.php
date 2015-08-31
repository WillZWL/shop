<?phpnamespace AtomV2\Dao;class ExtCategoryMappingDao extends BaseDao{    private $tableName = "ext_category_mapping";    private $voClassName = "extCategoryMappingVo";    private $debug = 0;    public function __construct()    {        parent::__construct();    }    public function getVoClassname()    {        return $this->voClassName;    }    public function getTableName()    {        return $this->tableName;    }    public function getGoogleCategoryMappingList($where = array(), $option = array(), $classname = "Google_category_mapping_dto")    {        $this->db->from("category c");        $this->db->join("ext_category_mapping ext_c", "ext_c.category_id = c.id and ext_c.ext_party='GOOGLEBASE'", "LEFT");        $this->db->join("external_category ext_c_2", "ext_c_2.id = ext_c.ext_id", "LEFT");        $this->include_dto($classname);        return $this->common_get_list($where, $option, $classname, 'c.id as category_id, c.name, ext_c.ext_id, ext_c.country_id,  ext_c_2.ext_name as google_category_name');    }    public function getCategoryCombination($where = array(), $option = array(), $classname = "")    {        $this->db->from("category c");        $this->db->join("category c1", "c.parent_cat_id = c1.id", "LEFT");        $this->db->join("category c2", "c1.parent_cat_id = c2.id", "LEFT");        $where['c.status'] = 1;        $where['c1.id Not in (22,27,56,57,67,68,69,70,71,135,288,289,290,308,309,310,313,314,315,316,317,318,326,327,342,343,357,369,370,392,399,400,401,430,435,443,458,477,481,489,490,502,511,512,513,514,531,533,570,571,594,603,622,687,720,721,722,748,750,751,752,826)'] = null;        $where['c.NAME NOT LIKE "%DO NO TUSE%"'] = null;        return $this->common_get_list($where, $option, $classname, "c.id as id, concat(c2.name, '->', c1.name,'->', c.name) as name");    }}