<?php 
 class Cart extends CI_Controller{
	public function __construct(){
        parent::__construct();
    }
    public function index(){
        $data['title']="Product Cart";
        $data['cartList']=$this->cart->contents();
        $this->load->store('shopping/cart',$data);
    }
    public function add($cart_id, $cart_qty=1){
        $pd = $this->db->get_where('products',array('product_id'=>$cart_id))->row();
        $this->cart->insert(array('id' => $pd->product_id,'product_id' => $pd->product_code, 'qty' => $cart_qty, 'price' => $pd->price, 'name' => $pd->name, 'thumb' => $pd->thumb ));
        $data['alert']=array(
            'status'=>'success',
            'title'=>'Product Added',
            'details'=>$cart_qty.' Product Add To Cart Successfully'
        );
        $this->session->set_flashdata('alert',$data['alert']);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
        $this->output->get_output();
    }

    public function add_load(){
        $this->add($this->input->post('id'), $this->input->post('qty'));
        return redirect('cart');
    }
    public function manage($qty=0){
        if (!$this->input->is_ajax_request()){ redirect(); die;}
        $this->cart->update(array('qty'=>$qty, 'rowid' => $this->input->post("row")));
        $data['alert']=array(
            'status' => $qty==0?'success':'warning',
            'title' => $qty==0?'Cart Product Removed':'Cart Product Updated',
            'details' => $qty==0?'Product Remove To Cart Successfully' : 'Cart Product Updated quantity is '.$qty
        );
        $this->session->set_flashdata('alert',$data['alert']);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
        $this->output->get_output();
        exit();
    }
    public function checkout(){
        if(count($this->cart->contents())>0){
            $data['title']="Product Checkout";
            $this->load->store('shopping/checkout',$data);
        }else{
            $this->session->set_flashdata('alert',array(
                'status' => 'warning',
                'title' => 'Empty Cart',
                'details' => 'Product Cart is Empty'
            ));
            return redirect('cart');
        }
    }
    public function complete(){
        $data['title']="Order Complete";
        $this->load->store('shopping/complete');
    }
    public function order(){
        $data['title']="Order Complete";
        $this->load->store('shopping/complete');
    }
    public function payment(){
        $data['title']="Order Complete";
        $this->load->store('shopping/complete');
    }
    public function data(){
        if (!$this->input->is_ajax_request()){ redirect(); die;}
        $cart_data=$this->cart->contents();
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($cart_data));
        $this->output->get_output();
    }
 }