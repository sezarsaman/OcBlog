<?php
class ControllerBlogPost extends Controller {
	public function index() {
		$this->load->language('blog/post');

		$this->load->model('blog/post');
        
        $this->load->model('tool/image');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
        
        $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_archive'),
			'href' => $this->url->link('blog/archive')
		);

		if (isset($this->request->get['post_id'])) {
			$post_id = (int)$this->request->get['post_id'];
		} else {
			$post_id = 0;
		}

		$post_info = $this->model_blog_post->getPost($post_id);

		if ($post_info) {
			$this->document->setTitle($post_info['meta_title']);
			$this->document->setDescription($post_info['meta_description']);
			$this->document->setKeywords($post_info['meta_keyword']);

			$data['breadcrumbs'][] = array(
				'text' => $post_info['title'],
				'href' => $this->url->link('blog/post', 'post_id=' .  $post_id)
			);

			$data['heading_title'] = $post_info['title'];

			$data['button_continue'] = $this->language->get('button_continue');

			$data['description'] = html_entity_decode(str_replace(HTTP_SERVER,HTTPS_SERVER,$post_info['description']), ENT_QUOTES, 'UTF-8');
            
            $images = $this->model_blog_post->getPostImages($post_info['post_id']);
            if (isset($images[0]) && $images[0] !=''){
                $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
                $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
                $this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');

                foreach($images as $k=>$image){
                    $data['images'][] = $this->model_tool_image->resize($image['image'], 1200, 450);
                }
            }else{
                $data['images'] = '';
            }
            
            $data['date'] = str_replace('-','.',$post_info['date']);
            
            $data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
            
            $this->response->setOutput($this->load->view('blog/post', $data));
            
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('blog/post', 'post_id=' . $post_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
    
}