<?php

/**
 * @package		Class
 * @subpackage	inc
 * @copyright	Copyright Neagle
 * @author		neagle2009@gmail.com
 * @since		2010-03-20
 * @version		$Id$
 */
class Pager
{
	
	public $iEachDispNum; //每页显示的记录数
	public $iCurrentPage; //当前页
	public $iPagesDispOneTime; //每次显示的页数
	public $iMaxPagesDispOneTime; //每页最多显示的页数
	public $iTotalRecord; //总记录数
	public $iTotalPage; //总页数
	public $PageArray = array ();
	public $sSubPageLink; //翻页连接前缀
	public $iPagesDispOneTimeType; //每次显示页数数组的形式
	public $aHeadEndStyle = array ('index' => '首页', 'end' => '尾页', 'font-page' => '上一页', 'next-page' => '下一页' );

	/**
	 * construct
	 * 
	 * @param $iEachDispNum int 每页显示的条目数
	 * @param $iTotalRecord int 总条目数
	 * @param $iCurrentPage int 当前被选中的页
	 * @param $mPagesDispOneTime mixed 每次显示的页数(int or str)
	 * @param $sSubPageLink string 每个分页的链接
	 * @param $iPagesDispOneTimeType int 显示分页的类型
	 */
	
	public function __construct($iEachDispNum, $iTotalRecord, $iCurrentPage, $mPagesDispOneTime, $sSubPageLink, $iPagesDispOneTimeType = 1)
	{
		$this->iEachDispNum = intval( $iEachDispNum );
		$this->iTotalRecord = intval( $iTotalRecord );
		$this->iTotalPage = ceil( $iTotalRecord/$iEachDispNum );
		$this->iCurrentPage = intval( $iCurrentPage )<1 ? 1 : intval( $iCurrentPage );
		if($this->iCurrentPage>$this->iTotalPage)
			$this->iCurrentPage = $this->iTotalPage;
			//类似搜索引擎的翻页,刚开始显示5页,最多显示10页
		if(strpos( $mPagesDispOneTime, ',' )===FALSE)
		{
			$this->iPagesDispOneTime = intval( $mPagesDispOneTime );
			//default 
			$this->iMaxPagesDispOneTime = $this->iPagesDispOneTime*2;
		}
		else
		{
			$ar = explode( ',', $mPagesDispOneTime, 2 );
			$this->iPagesDispOneTime = intval( $ar [0] );
			$this->iMaxPagesDispOneTime = intval( $var [1] );
		}
		$this->sSubPageLink = $sSubPageLink;
		$this->iPagesDispOneTimeType = $iPagesDispOneTimeType;
		$this->LimitInfo();
		//return $this->ShowSubPage ( $iPagesDispOneTimeType );
		return;
	}

	/**
	 * 而且用来判断显示什么样子的分页
	 * 
	 * @param int $iPagesDispOneTimeType
	 */
	public function ShowSubPage()
	{
		if(!method_exists( $this, 'SubPageCss'.$this->iPagesDispOneTimeType ))
			exit( 'Give the wrong param "$ipagesDispOneTimeType"!' );
		return $this->{'SubPageCss'.$this->iPagesDispOneTimeType}();
	}

	/**
	 * 用来构造显示的条目 例:[1][2][3][4][5][6][7][8][9][10]
	 * 
	 * @return array
	 */
	public function GetPageArray()
	{
		$page_start_info = $this->iCurrentPage<$this->iPagesDispOneTime ? 0 : floor( ($this->iCurrentPage-1)/$this->iPagesDispOneTime );
		//不到一页
		if($this->iTotalPage<$this->iPagesDispOneTime)
		{
			for($i = 0;$i<$this->iTotalPage;$i++)
			{
				$pages_array [$i] = $i+1;
			}
		}
		elseif($this->iCurrentPage<=$this->iTotalPage-($this->iTotalPage%$this->iPagesDispOneTime))
		{
			for($i = 0;$i<$this->iPagesDispOneTime;$i++)
			{
				$pages_array [$i] = $page_start_info*$this->iPagesDispOneTime+$i+1;
			}
		}
		//最后剩下的不到一页
		else
		{
			$mod = $this->iTotalPage%$this->iPagesDispOneTime;
			for($i = 0;$i<$mod;$i++)
			{
				$pages_array [$i] = $page_start_info*$this->iPagesDispOneTime+$i+1;
			}
		}
		return $pages_array;
	}

	/**
	 * 构造普通模式的分页 例:共4523条记录,每页显示10条,当前第1/453页 [首页] [上页] [下页] [尾页]
	 * 
	 * @return string
	 */
	public function SubPageCss1()
	{
		$sub_page_css1_str = "";
		$sub_page_css1_str .= "共".$this->iTotalRecord."条记录，";
		$sub_page_css1_str .= "每页显示".$this->iEachDispNum."条，";
		$sub_page_css1_str .= "当前第".$this->iCurrentPage."/".$this->iTotalPage."页 ";
		if($this->iCurrentPage>1)
		{
			$firstPageUrl = $this->sSubPageLink."1";
			$prewPageUrl = $this->sSubPageLink.($this->iCurrentPage-1);
			$sub_page_css1_str .= "[<a href='$firstPageUrl'>".$this->aHeadEndStyle ['index']."</a>] ";
			$sub_page_css1_str .= "[<a href='$prewPageUrl'>".$this->aHeadEndStyle ['font-page']."</a>] ";
		}
		else
		{
			$sub_page_css1_str .= $this->aHeadEndStyle ['index'];
			$sub_page_css1_str .= $this->aHeadEndStyle ['font-page'];
		}
		
		if($this->iCurrentPage<$this->iTotalPage)
		{
			$lastPageUrl = $this->sSubPageLink.$this->iTotalPage;
			$nextPageUrl = $this->sSubPageLink.($this->iCurrentPage+1);
			$sub_page_css1_str .= " [<a href='$nextPageUrl'>".$this->aHeadEndStyle ['next-page']."</a>] ";
			$sub_page_css1_str .= "[<a href='$lastPageUrl'>".$this->aHeadEndStyle ['end']."</a>] ";
		}
		else
		{
			$sub_page_css1_str .= $this->aHeadEndStyle ['next-page'];
			$sub_page_css1_str .= $this->aHeadEndStyle ['end'];
		}
		return $sub_page_css1_str;
	}

	/**
	 * 构造经典模式的分页 例:当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]
	 * 
	 * @return string
	 */
	public function SubPageCss2()
	{
		$sub_page_css2_str = "";
		$sub_page_css2_str .= "当前第".$this->iCurrentPage."/".$this->iTotalPage."页 ";
		if($this->iCurrentPage>1)
		{
			$firstPageUrl = $this->sSubPageLink."1";
			$prewPageUrl = $this->sSubPageLink.($this->iCurrentPage-1);
			$sub_page_css2_str .= "[<a href='$firstPageUrl'>".$this->aHeadEndStyle ['index']."</a>] ";
			$sub_page_css2_str .= "[<a href='$prewPageUrl'>".$this->aHeadEndStyle ['font-page']."</a>] ";
		}
		else
		{
			$sub_page_css2_str .= $this->aHeadEndStyle ['index'];
			$sub_page_css2_str .= $this->aHeadEndStyle ['font-page'];
		}
		//$a = $this->ConstructNumPage ();
		$a = $this->GetPageArray();
		$size = sizeof( $a );
		for($i = 0;$i<$size;$i++)
		{
			if($a [$i]==$this->iCurrentPage)
			{
				$sub_page_css2_str .= "<span style='color:red;font-weight:bold;'>".$a [$i]."</span>";
			}
			else
			{
				$url = $this->sSubPageLink.$a [$i];
				$sub_page_css2_str .= "[<a href='$url'>".$a [$i]."</a>]";
			}
		}
		if($this->iCurrentPage<$this->iTotalPage)
		{
			$lastPageUrl = $this->sSubPageLink.$this->iTotalPage;
			$nextPageUrl = $this->sSubPageLink.($this->iCurrentPage+1);
			$sub_page_css2_str .= " [<a href='$nextPageUrl'>".$this->aHeadEndStyle ['next-page']."</a>] ";
			$sub_page_css2_str .= "[<a href='$lastPageUrl'>".$this->aHeadEndStyle ['end']."</a>] ";
		}
		else
		{
			$sub_page_css2_str .= $this->aHeadEndStyle ['next-page'];
			$sub_page_css2_str .= $this->aHeadEndStyle ['end'];
		}
		return $sub_page_css2_str;
	}

	/**
	 * 搜索引擎式的翻页(参考Google)
	 *
	 * @return String
	 */
	public function SubPageCss3()
	{
		$sub_page_css = '';
		if($this->iCurrentPage==1)
		{
			//$page_array = $this->GetPageArray ();
			$length = ($this->iTotalPage>$this->iPagesDispOneTime) ? $this->iPagesDispOneTime : $this->iTotalPage;
			$pages_array = range( 1, $length );
			print_r( $pages_array );
			$size = sizeof( $pages_array );
			$sub_page_css .= '<span style="font-color:red">1</span>';
			for($i = 1;$i<$size;$i++)
			{
				$sub_page_css .= ' <a href="'.$this->sSubPageLink.$pages_array [$i].'">'.$pages_array [$i].'</a> ';
			}
			if($this->iTotalPage>$length)
				$sub_page_css .= ' <a href="'.$this->sSubPageLink.($this->iCurrentPage+1).'">'.$this->aHeadEndStyle ['next-page'].'</a> ';
		}
		else
		{
			$sub_page_css .= '<a href="'.$this->sSubPageLink.($this->iCurrentPage-1).'">'.$this->aHeadEndStyle ['font-page'].'</a>';
			$pages_array = array ();
			$fonter_num = ceil( $this->iMaxPagesDispOneTime/2 );
			$latter_num = $this->iMaxPagesDispOneTime-$fonter_num-1;
			//$last_page = $this->iTotalPage>($this->iCurrentPage+$this->iMaxPagesDispOneTime-1)?($this->iCurrentPage+$this->iMaxPagesDispOneTime-1):$this->iTotalPage;
			$start = $this->iCurrentPage-$fonter_num>0 ? $this->iCurrentPage-$fonter_num : 1;
			$end = $this->iCurrentPage+$latter_num>$this->iTotalPage ? $this->iTotalPage : ($this->iCurrentPage+$latter_num);
			$pages_array = range( $start, $end );
			$size = sizeof( $pages_array );
			for($i = 0;$i<$size;$i++)
			{
				if($this->iCurrentPage==$pages_array [$i])
					$sub_page_css .= '<span style="font-color:red">'.$this->iCurrentPage.'</span>';
				else
					$sub_page_css .= ' <a href="'.$this->sSubPageLink.$pages_array [$i].'">'.$pages_array [$i].'</a> ';
			}
			if($this->iCurrentPage!=$end)
				$sub_page_css .= ' <a href="'.$this->sSubPageLink.($this->iCurrentPage+1).'">'.$this->aHeadEndStyle ['next-page'].'</a> ';
		}
		return $sub_page_css;
	}

	/**
	 * 首页,尾页,上一页,下一页样式的设定
	 *
	 * @param String $str	首页,尾页,上一页,下一页
	 */
	public function HeadEndStyle($str)
	{
		$style = explode( ',', $str );
		$this->aHeadEndStyle ['index'] = $style [0];
		$this->aHeadEndStyle ['end'] = $style [1];
		$this->aHeadEndStyle ['font-page'] = $style [2];
		$this->aHeadEndStyle ['next-page'] = $style [3];
		return;
	}

	/**
	 * 设定Limit信息
	 *
	 */
	public function LimitInfo()
	{
		$this->sLimit ['start'] = ($this->iCurrentPage-1)*$this->iPagesDispOneTime;
		$this->sLimit ['length'] = $this->iPagesDispOneTime;
		return;
	}

	/**
	 * 跳转到第几页
	 *
	 * @return String
	 */
	public function GetGotoHtml()
	{
		$html = "第<input id='go_to_page' size='4' />页,<button onclick='GoToPage();'>转到</button>
			<script type='text/javascript'>
			function GoToPage()
			{
				var page=document.getElementById('go_to_page');
				if(isNaN(page.value)||page.value=='')
				{
					alert('必须输入数字!');
					page.focus();
					page.value = '';
					return false;
				}
				page.value = parseInt(page.value)<1?1:parseInt(page.value);
				page.value = parseInt(page.value)>{$this->iTotalPage}?{$this->iTotalPage}:parseInt(page.value);
				window.location=\"{$this->sSubPageLink}\"+page.value;
			}
			</script>";
		return $html;
	}

	/**
	 * 自动生成表格数据(不太完善)
	 *
	 * @param array $title	表头字段名
	 * @param array $data	数据内容(MYSQL_ASSOC OR MYSQL_NUM) 
	 * @return String
	 */
	public function GetTableHtml($title, $data)
	{
		$info = '';
		if(!empty( $data )&&sizeof( $data [0] ))
		{
			$info .= '<table>';
			$info .= '<thead><tr>';
			foreach( $title as $v )
			{
				$info .= '<td>'.$v.'</td>';
			}
			$info .= '</tr></thead>';
			$data_size = sizeof( $title ); //标题和数据是一对一的,数据可能有MYSQL_BOTH形式的
			$info .= '<tbody>';
			for($i = 0;$i<$data_size;$i++)
			{
				$info .= '<tr>';
				//不好处理,数据的顺序
				foreach( $data [$i] as $v )
				{
					$info .= '<td>'.$v.'</td>';
				}
				$info .= '</tr>';
			}
			$info .= '</tbody>';
			$info .= '<tfoot></tfoot>';
			$info .= '</table>';
		}
		else
		{
			//没有数据的情况
			$info = '<p>没有查到相关数据!</p>';
		}
		return $info;
	}

	public function __destruct()
	{
		unset( $iEachDispNum );
		unset( $iTotalRecord );
		unset( $iCurrentPage );
		unset( $iPagesDispOneTime );
		unset( $iTotalRecord );
		unset( $PageArray );
		unset( $sSubPageLink );
		unset( $iPagesDispOneTimeType );
	}
}
?>
