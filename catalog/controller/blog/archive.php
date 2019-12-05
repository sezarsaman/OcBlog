<?php
class ControllerBlogArchive extends Controller {
	public function index() {
        $this->load->language('blog/archive');

		$this->load->model('blog/post');

        $this->load->model('tool/image');
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_archive'] = $this->language->get('text_archive');
        
        $this->document->setTitle( $this->language->get('heading_title') );
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
        
        $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_archive'),
			'href' => $this->url->link('blog/archive')
		);
        
        $data['posts'] = array();

		foreach ( $this->model_blog_post->getPosts() as $result ) {
		    $images = $this->model_blog_post->getPostImages($result['post_id']);
            if(isset($images[0]['image']))
                $image = $this->model_tool_image->resize($images[0]['image'], 400, 150);
            else
                $image = '';
                
            $desc_raw = strip_tags(html_entity_decode($result['summary'], ENT_QUOTES, 'UTF-8'));
            $des_array = explode(' ',$desc_raw);
            $description = '';
            for ($d=0;$d<40;$d++){
                if(isset($des_array[$d]) && $des_array[$d] !='')
                    $description .= $des_array[$d].' ';
                else
                    break;
            }
                
            $data['posts'][] = array(
                'title'       => $result['title'],
                'href'        => $this->url->link('blog/post', 'post_id=' . $result['post_id']),
                'description' => $description . ' ... <a href="'.$this->url->link('blog/post', 'post_id=' . $result['post_id']).'">' . $this->language->get('readmore') .'</a>',
                'image'       => $image
            );
		}
        
        $data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
        
        $this->response->setOutput($this->load->view('blog/archive', $data));
    }
}