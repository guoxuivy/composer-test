<?php
namespace composer\test;
/**
 * 共用独立相关，高内聚方便后期移植封装
 * 禁止业务强关联，必须保证 与选址顾问能无缝移植
 */
trait StoreTrait {

	/**
	 * 初始化时需要主动调用
	 * @return [type] [description]
	 */
	protected function storeTraitInit(){
		$this->field=$this->_store_field;
		$this->type=$this->_store_type;
	}

	/**
     * 获取当前模型的数据库查询对象
     * @access public
     * @param bool $useBaseQuery 是否调用全局查询范围
     * @param bool $buildNewQuery 创建新的查询对象
     * @return Query
     */
    public function db($useBaseQuery = true, $buildNewQuery = true)
    {
    	$query = $this->getQuery($buildNewQuery);
    	// 全局作用域
    	if ($useBaseQuery && method_exists($this, 'base')) {
    		call_user_func_array([$this, 'base'], [ & $query]);
    	}
    	$this->after_db($query);
    	// 返回当前模型的数据库查询对象
    	return $query;
    }

    /*public function setLesseeDateAttr($value){
        if(strtotime(date('Y-m-d H:i:s',$value))===$value){
            return (int)$value;
        }else{
            return (int)strtotime($value);
        }
    }*/
    public function setArrivalDateAttr($value){
        if(strtotime(date('Y-m-d H:i:s',$value))===$value){
            return (int)$value;
        }else{
            return (int)strtotime($value);
        }
    }
   /* public function setExtensionDateAttr($value){
        if(strtotime(date('Y-m-d H:i:s',$value))===$value){
            return (int)$value;
        }else{
            return (int)strtotime($value);
        }
    }*/
    public function setExceptedDateAttr($value){
        if(strtotime(date('Y-m-d H:i:s',$value))===$value){
            return (int)$value;
        }else{
            return (int)strtotime($value);
        }
    }

    public function getLesseeDateAttr($value){
        if($value){
            return date("Y-m-d",$value);
        }else{
            return $value;
        }
    }
    public function getArrivalDateAttr($value){
        if($value){
            return date("Y-m-d",$value);
        }else{
            return $value;
        }
    }
    /*public function getExtensionDateAttr($value){
        if($value){
            return date("Y-m-d",$value);
        }else{
            return $value;
        }
    }*/
    public function getExceptedDateAttr($value){
        if($value){
            return date("Y-m-d",$value);
        }else{
            return $value;
        }
    }

    public function isAudited(){
    	return $this->getData('is_audited')>1?true: false;
    }

    public function getAuditedAttr(){
    	return $this->isAudited();
    }

    public function getAuditedStatusAttr(){
        $arr = [0 => '审核中', 1 => '审核未通过', 2 => '已电话核实', 3 => '已现场核实'];
        return $arr[$this->getData('is_audited')];
    }

    public function getEntrustStatusAttr() {
        $arr = [0 => '审核中', 1 => '审核未通过', 2 => '已电话核实', 3 => '已现场核实'];
        return $arr[$this->getData('is_entrust')];
    }

    public function getHasEntrustAttr(){
    	return $this->getData('is_entrust')==2?true: false;
    }

    public function getBusinessTypeValAttr() {
        $bt = $this->getData('business_type');
        if (!is_numeric($bt)) {
            return $bt;
        } else {
            $arr = [1 => '出租', 2 => '出售', 3 => '租售皆可', 4 => '出租'];
            return $arr[$bt];
        }
    }

    public function showResourceType(){
        $row =  $this->getData();
        if($row['ware_type']==1){
            return "仓库";
        }elseif($row['ware_type']==2){
            return "厂房";
        }elseif($row['ware_type']==3){
            return "库房";
        }
    }

	private $_store_field = [
        "id",
        "code", //'编号'
        "user_id", //'发布用户id'
        "park_id", //'所属园区'
        "number",//'栋座编号'
        "ware_type",//库房用途 1仓库 2厂房 3两者均可
        "business_type", //'1出租/2出售/3租售皆可/4转租'
        "is_cut", //'是否可分租'
        "is_cut_sale", //'是否可分售'
        "address", //'详细地址'
        "map_location",//'地图标记'
        "total_area", //'总面积'
        "usable_area", //'可租面积'
        "no_usable_area_use", //'无可租面积是否可报价 1 接受报价'
        "start_usable_area", //'起租面积'
        "surface_rent", //'租金面价'  *租金一律转换为按月存
        "surface_rent_currency", //显示类型 按月/天显示 
        "surface_rent_unit", //'租金面价单位;1平米/2立方米'        #废除
        "surface_rent_type", //'租金面价类型;1月/2天/3年'            #废除
        "upset_rent", //'内部底价'
        "upset_rent_currency", //显示类型 按月/天显示 
        "upset_rent_unit", //'内部底价单位;1平米/2立方米'          #废除
        "upset_rent_type", //'内部低价类型;1月/2天/3年'              #废除
        "saleable_area", //'可售面积'
        "start_saleable_area", //'起售面积'
        "selling_price", //'售价'  元/平米
        "selling_price_currency", //'售价币种;1元/2美元/3欧元/4港币' #废除
        "selling_price_unit", //'售价单位;1平米/2立方米'             #废除
        "rent_month", //'起租年限'
        "service_range", //'服务范围'
        "arrival_date", //'入驻日期'
        "arrival_customer", //'入驻客户要求'
        "description", //'仓库描述'
        "owner_identity", //'业主身份';1产权拥有人 2经营方/承租方
        "company", //'所属企业'
        "tel", //'固定电话'
        "email", //'企业邮箱'
        "person_email", //'个人邮箱'
        "contact", //'业主姓名'
        "position", //'业主职位'
        "sex", //'性别'
        "phone", //'业主电话'
        "identity_relet", //'转租方身份'             #废除
        "company_relet", //'转租方所属企业'        #废除
        "tel_relet", //'转租方固定电话'                #废除
        "email_relet", //'转租方企业邮箱'                   #废除
        "person_email_relet", //'转租方个人邮箱'       #废除
        "contact_relet", //'转租方业主姓名'         #废除
        "sex_relet", //'性别'                         #废除
        "phone_relet", //'转租方业主电话'              #废除
        "warehouse_type", //'库型选择'
        "warehouse_application", //'仓库应用'
        "landing_platform", //'卸货平台' 
        "fire_control", //'消防资质' 甲乙丙丁。。。
        "building_type", //'建筑类型'
        "building_standard", //'建筑结构'
        "total_floor", //'总楼层'
        "bottom_height", //'底层层高'
        "over_bottom_height", //'二层及以上层高'
        "is_factory", //'是否可做厂房'                #废除
        "is_reform", //'客户可否改造'
        "property_card", //'产权证'
        "land_card", //'土地证'
        "build_date", //'建造日期'
        "useful_date", //'可使用年限'
        "is_equipments", //'库内有无设备'
        "library_equipment", //'库内设备'
        "terrace_quality", //'地坪质地'
        "bottom_bearing", //'底层承重'
        "over_bottom_bearing", //'二层及以上承重'
        "apply_field", //'适用领域'
        "water_powrer", //'库内水电网'
        "power_rate", //'电费'
        "water_rate", //'水费'
        "max_power", //'最大用电功率'
        "firecontrol_system", //'消防系统'
        "security_system", //'安保系统'
        "other_system", //'其他设施'
        "width_depot", //'停车场通道宽度'
        "max_stay_people", //'最多可住宿人数'
        "max_repast_people", //'最大就餐人数'
        "dormitory_area", //'宿舍面积'
        "depot_area",//停车场面积
        "totalfloors_office", //'办公室总楼层'
        "total_office", //'办公室面积'
        "overall_view_picurl", //'仓库全景图'
        "location_picurl", //'仓库外景图'
        "interior_picurl", //'仓库内景图'
        "periphery_picurl", //'周边环境'
        "other_picurl", //'其他图片'
        "is_audited", //'是否审核通过'
        "level_fail_reason", //'加V申请失败原因'
        "v_level", //'v等级' 0-默认值,1-审核失败,2-等待加V审核,3-加V审核通过
        "is_supervise", //'是否监管仓'
        "is_entrust", //'是否已委托'
        "is_hot", //'是否热力推荐'
        "is_finished", //'是否发布完成' 0:草稿 1:已发布 2:等待发布中
        "is_undercarriage", //'是否下架'
        "delete_status", //'删除状态'
        "create_date", //'创建时间'
        "modify_date", //'更新时间'
        "view", //'访问量'
        "key_words", //'搜索关键字'
        "agent_user_id", //'代发人' 后台管理员id
        "audit_fail_reason", //'审核未通过原因'
        "entrust_fail_reason", //'委托未通过原因'
        "entrust_ok_remark",   //'委托通过备注'
        "undercarriage_reason", //'下架原因'
        "lat", //'纬度'
        "lon", //'经度'
        "province",
        "city",
        "region",
        "provincecode", //'省份code'
        "citycode", //'市code'
        "regioncode", //'区域code'
        "temperature_min", //温度区间低温
        "temperature_max", //温度区间高温
        "door_number", //仓洞门个数
        "door_width", //门洞宽
        "door_height", //门洞高
        "landing_type", //月台类型 ；1：内嵌式；2：外嵌式
        "is_landing_up", //是否可升降
        "landing_width", //月台宽
        "landing_height",//月台高
        "is_canopy", //有无雨棚
        "canopy_width", //雨棚宽
        "site_width", //周转场地宽度
        "is_ramp", //行车坡道
        "excepted_date", //预计获得日期
        "fire_certificate", //消防验收证明
        "light_level", //仓库照度
        "light_cover", //屋面采光
        "production_field", //适用生产领域 用作仓房 
        "acceptable_pollution", //可接受污染 用作仓房 
        "factory_apply_field", //适用生产类型 用作仓房 
        //"extension_date", //未来可扩展日期  已废弃
        //"extension_area", //未来可扩展面积  已废弃
        "extension", //未来可扩展面积
        "remark",//库房备注
        "from_type", //来源 0：仓小二 1：选址顾问
        "ware_width",//库房宽度
        "ware_length",//库房长度
        "column_length",//柱网跨度长
        "column_width",//柱网跨度宽
        "over_column_length",//二层以上柱网跨度长
        "over_column_width",//二层以上柱网跨度宽
        "is_light_machining",//可否轻加工
        "is_landing_num",//升降平台总数
        "ramp_width",//坡道宽度
        "owner_company",//产权实际归属企业
        "lessee",//经营/承租楼层
        "owner_company_area",//经营/承租总面积
        "lessee_date",//经营/承租截止日期
        "free_rent",//免租期
        "free_rent_unit",//免租期单位
        "invoice",//发票开具
        "tax",//税点
        "over_surface_rent_min",//二层及以上租金面价
        "over_surface_rent_max",//二层及以上租金面价
        "over_surface_rent_currency",//二层及以上租金面价单位      #废除
        "over_upset_rent_min",//二层及以上内部底价
        "over_upset_rent_max",//二层及以上内部底价
        "over_upset_rent_currency",//二层及以上内部底价单位        #废除
        "over_selling_price_min",//二层及以上售价
        "over_selling_price_max",//二层及以上售价
        "express_site" ,    //周边快递分拨中心 [0]名称 [1]距离
        "transfer_company" ,   //周边零担运输企业 [0]名称 [1]距离
        "traffic" , //周边交通情况
        "lane_num" , //车道信息
        "management_system" , //管理系统
        "build_area", //建筑面积       #废除
        "is_property" , //有无物业公司'
        "property_company" , //物业公司
        "property_fee",//物业费
        "is_emergency_generator" , //有无发电机
        "visual" ,//在那个平台可以展示的字段 1.仓小二 2.选址顾问 3.都可展示
        'rent_price',//租金，自动转成按月
        "adviser_id" , //代fa 顧問id
        "classification" , //存放货品分类
        "factory_classification" , //生产产品分类
        "rent_floor" , //租售楼层
        'admin_sort', //后台排序
        'area_office', //办公室面积
        'title', //标题
        //'number_id', //主栋座的id
        'building_id',
        "owner_id", //管理人员id
        //"contact_id", //招商人员id
        "creator_id", //发布人员id
		'enterprise_id',
    ];

    private $_store_type   = [
        "adviser_id"=>'integer',
        "ware_type"=>'integer',
        "build_date"=>'integer',
        "user_id"=>'integer',
        "agent_user_id"=>'integer',
        "total_area"=>'float' , //'总面积'
        "usable_area"=>'float' , //'可租面积'
        "no_usable_area_use"=>'integer',//无可租面积是否接受报价
        "start_usable_area"=>'float' , //'起租面积'
        "surface_rent"=>'float' , //'租金面价'
        "upset_rent"=>'float' , //'内部底价'
        "saleable_area"=>'float' , //'可售面积'
        "start_saleable_area"=>'float' , //'起售面积'
        "selling_price"=>'float' , //'售价'
        "service_range"=>'array' , //'服务范围'
        "water_powrer"=>'array',
        "other_system"=>'array',
        "apply_field"=>'array',
        "factory_apply_field"=>'array',
        "building_standard"=>'array',
        "terrace_quality"=>'array',
        "firecontrol_system"=>'array',
        "fire_control"=>'integer',
        "security_system"=>'array',
        "library_equipment"=>'array',
        "is_audited"=>'integer',
        "v_level"=>'integer',
        "is_supervise"=>'integer',
        "is_entrust"=>'integer',
        "is_hot"=>'integer',
        "is_undercarriage"=>'integer',
        "is_finished"=>'integer',
        "delete_status"=>'integer',
        "view"=>'integer',
        "business_type"=>'integer',
        "is_cut"=>'integer',
        "is_cut_sale"=>'integer',
        "surface_rent_currency"=>'integer',
        "upset_rent_currency"=>'integer',
        "provincecode"=>'integer',
        "citycode"=>'integer',
        "regioncode"=>'integer',
        "building_type"=>'integer',
        "remark"=>'string',
        "from_type"=>'integer',
        "lat"=>'float',
        "lon"=>'float',
        "depot_area"=>'integer',
        "express_site"=>'array',
        "transfer_company"=>'array',
        "traffic"=>'array',
        "lane_num"=>'array',
        "ware_width"=>'float',
        "ware_length"=>'float',
        "column_length"=>'integer',
        "column_width"=>'integer',
        "over_column_length"=>'float',
        "over_column_width"=>'float',
        "is_light_machining"=>'integer',
        "is_landing_num"=>'integer',
        "ramp_width"=>'integer',
        "lessee"=>'array',
        "owner_company_area"=>'integer',
        "free_rent"=>'float',
        "free_rent_unit"=>'integer',
        "invoice"=>'integer',
        "over_surface_rent_min"=>'float',
        "over_surface_rent_max"=>'float',
        "over_surface_rent_currency"=>'integer',
        "over_upset_rent_min"=>'float',
        "over_upset_rent_max"=>'float',
        "over_upset_rent_currency"=>'integer',
        "over_selling_price_min"=>'float',
        "over_selling_price_max"=>'float',
        "management_system"=>'array',
        "other_picurl"=>'array',
        "property_card"=>'integer',
        "land_card"=>'integer',
        "visual"=>'integer' ,
    	'bottom_height' => 'float',
    	'over_bottom_height' => 'float',
    	'is_reform' => 'integer',
    	'classification' => 'integer',
    	'factory_classification' => 'integer',
    	'production_field' => 'array',
    	'acceptable_pollution' => 'array',
        'tax' => 'float',
        'rent_floor' => 'array',
        "warehouse_type"=>'integer',
        "warehouse_application"=>'integer',
        "landing_platform"=>'integer',
        "landing_type"=>"integer",
        "is_canopy"=>"integer",
        "create_date"=>"integer",
        "modify_date"=>"integer",
        //"lessee_date"=>"integer",
        "arrival_date"=>"integer",
        "excepted_date"=>"integer",
    	'admin_sort' => 'integer',
    	'area_office' => 'float',
        "owner_id"=>"array",
        //"contact_id"=>"array",
        "extension"=>"array",
        "creator_id"=>"integer",
		'enterprise_id' => 'integer',
        "is_emergency_generator"=>'integer',

    ];

	
}