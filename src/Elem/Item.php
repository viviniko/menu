<?php

namespace Viviniko\Menu\Elem;

use Illuminate\Support\Str;

class Item
{
	/**
	 * Reference to the menu builder
	 *
	 * @var Builder
	 */
	protected $builder;

	/**
	 * The ID of the menu item
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * Item's title 
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Item's name in camelCase
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Item's seprator from the rest of the items, if it has any.
	 *
	 * @var array
	 */
	public $divider = array();

	/**
	 * Parent Id of the menu item
	 *
	 * @var int
	 */
	protected $parent;
	
	/**
	 * Extra information attached to the menu item
	 *
	 * @var array
	 */
	protected $data = array();
	
	/**
	 * Attributes of menu item
	 *
	 * @var array
	 */
	public $attributes = array();
	
	/**
	 * Flag for active state
	 *
	 * @var bool
	 */
	public $isActive = false;
	
	/**
	 * Creates a new MenuItem instance.
	 *
	 * @param  Builder  $builder
     * @param  string  $id
     * @param  string  $title
     * @param  array  $options
	 * @return Item
	 */
	public function __construct($builder, $id, $title, $options)
	{
		$this->builder     = $builder;
		$this->id          = $id;
		$this->title       = $title;
		$this->name        = isset($options['name']) ? $options['name'] : camel_case(Str::ascii($title));
		$this->attributes  = $this->builder->extractAttributes($options); 
		$this->parent      = (is_array($options) && isset($options['parent'])) ? $options['parent'] : null;

		// Storing path options with each link instance.
		if (!is_array($options)) {
			$path = array('url' => $options);
		} else if (isset($options['raw']) && $options['raw'] == true) {
			$path = null;
		} else {
			$path = array_only($options, array('url', 'route', 'action', 'secure'));
		}
		if (!is_null($path)) {
			$path['prefix'] = $this->builder->getLastGroupPrefix();
		}
		$this->link = $path ? new Link($path) : null;
		// Activate the item if items's url matches the request uri
		if (true === $this->builder->options('auto_activate')) {
			$this->checkActivationStatus();
		} 
	}

	/**
	 * Creates a sub Item
	 *
	 * @param  string  $title
	 * @param  array  $options
	 * @return void
	 */
	public function add($title, array $options)
	{
		if(!is_array($options)) {
			$url = $options;
			$options = array();
			$options['url'] = $url;
		}
		
		$options['parent'] = $this->id;
				
		return $this->builder->add($title, $options);
	}

	/**
	 * Add a plain text item
	 *
     * @param string $title
     * @param array $options
	 * @return Item
	 */
	public function raw($title, array $options = array())
	{
		$options['parent'] = $this->id;
		
		return $this->builder->raw($title, $options);
	}

	/**
	 * Insert a seprator after the item
	 *
	 * @param array $attributes
	 * @return void
	 */
	public function divide($attributes = array())
    {
		$attributes['class'] = Builder::formatGroupClass($attributes, array('class' => 'divider'));
		$this->divider = $attributes;

		return $this;
	}


	/**
	 * Group children of the item
	 *
	 * @param  array $attributes
	 * @param  callable $closure
	 * @return void
	 */
	public function group($attributes, $closure)
	{
		$this->builder->group($attributes, $closure, $this);
	}

	/**
	 * Add attributes to the item
	 *
	 * @param  mixed
	 * @return mixed
	 */
	public function attr()
	{
		$args = func_get_args();

		if (isset($args[0]) && is_array($args[0])) {
			$this->attributes = array_merge($this->attributes, $args[0]);
			return $this;
		} else if (isset($args[0]) && isset($args[1])) {
			$this->attributes[$args[0]] = $args[1];
			return $this;
		} else if (isset($args[0])) {
			return isset($this->attributes[$args[0]]) ? $this->attributes[$args[0]] : null;
		}

		return $this->attributes;
	}

	/**
	 * Generate URL for link
	 *
	 * @return string
	 */
	public function url()
    {
        // If the item has a link proceed:
        if(!is_null($this->link)) {
            // If item's link has `href` property explcitly defined
            // return it
            if($this->link->href) {
                return $this->link->href;
            }
            // Otherwise dispatch to the proper address
            return $this->builder->dispatch($this->link->path);
        }
	}


	/**
	 * Prepends text or html to the item
	 *
	 * @return $this
	 */
	public function prepend($html)
	{
		$this->title = $html . $this->title;
	
		return $this;
	}

	/**
	 * Appends text or html to the item
	 *
	 * @return $this
	 */
	public function append($html)
	{
		$this->title .= $html;
		
		return $this;
	}

	/**
	 * Checks if the item has any children
	 *
	 * @return boolean
	 */
	public function hasChildren()
	{
		return count($this->builder->whereParent($this->id)) or false;
	}

	/**
	 * Returns childeren of the item
	 *
	 * @return Collection
	 */
	public function children()
	{
		return $this->builder->whereParent($this->id);
	}

	/**
	 * Returns all childeren of the item
	 *
	 * @return Collection
	 */
	public function all()
	{
		return $this->builder->whereParent($this->id, true);
	}

	/**
	 * Decide if the item should be active
	 */
	public function checkActivationStatus()
    {
        $activateItems = $this->builder->options('activate_items');
        if (is_array($activateItems) && in_array($this->id, $activateItems)) {
            $this->activate();
        } else if ( $this->builder->options('restful') == true ) {
			$path  = ltrim(parse_url($this->url(), PHP_URL_PATH), '/');
			$rpath = \Request::path();

			$restBase = $this->builder->options('rest_base');
			if($restBase) {
				$base = ( is_array($restBase) ) ? implode('|', $restBase) : $restBase;
				list($path, $rpath) = preg_replace('@^('. $base . ')/@', '' , [$path, $rpath], 1);
			}

			if( preg_match("@^{$path}(/.+)?\z@", $rpath) ) {
				$this->activate();
			}
		} else {
			if( $this->url() == \Request::url() ) {
				$this->activate();
			}
		}
	}

	/**
	* Set name for the item manually
	*
	* @param string $name
	* @return Item
	*/
	public function name($name = null)
    {
		if (is_null($name)) {
			return $this->name;
		}
		$this->name = $name;

		return $this;
	}

	/**
	* Set id for the item manually
	*
	* @param string $id
	* @return Item
	*/
	public function id($id = null)
    {
		if (is_null($id)) {
			return $this->id;
		}
		$this->id = $id;

		return $this;
	}

	/**
	 * Activat the item
	 *
	 * @param Item $item
	 */
	public function activate(Item $item = null)
    {
		$item = is_null($item) ? $this : $item;
		
		// Check to see which element should have class 'active' set.
		if($this->builder->options('active_element') == 'item') {
			$item->active();
		} else {
			$item->link->active();
		}	
		
		// If parent activation is enabled:
		if( true === $this->builder->options('activate_parents') ){
			// Moving up through the parent nodes, activating them as well.
			if($item->parent) {
			    if ($parent = $this->builder->whereId( $item->parent )->first()) {
                    $this->activate($parent);
                } else {
                    $this->builder->options('activate_items',
                                            array_merge($this->builder->options('activate_items') ?: [], [$item->parent]));
                }
			}
		}
	}

	/**
	 * Make the item active
	 *
	 * @return $this
	 */
	public function active($pattern = null)
    {
		if(!is_null($pattern)) {
			$pattern = ltrim(preg_replace('/\/\*/', '(/.*)?', $pattern), '/');
			if( preg_match("@^{$pattern}\z@", \Request::path()) ){
				$this->activate();
			}	

			return $this;
		}

		$this->attributes['class'] = Builder::formatGroupClass(array('class' => $this->builder->options('active_class')), $this->attributes);
		$this->isActive = true;
		
		return $this;
	}

	/**
	 * Set or get items's meta data
	 *
	 * @param  mixed
	 * @return mixed
	 */
	public function data()
	{
		$args = func_get_args();

		if (isset($args[0]) && is_array($args[0])) {
			$this->data = array_merge($this->data, array_change_key_case($args[0]));
			
			// Cascade data to item's children if cascade_data option is enabled
			if($this->builder->options('cascade_data')) {
				$this->cascadeData($args);
			}

			return $this;
		} else if (isset($args[0]) && isset($args[1])) {
			$this->data[strtolower($args[0])] = $args[1];
			
			// Cascade data to item's children if cascade_data option is enabled
			if($this->builder->options('cascade_data')) {
				$this->cascadeData($args);
			}

			return $this;
		} else if (isset($args[0])) {
			return isset($this->data[$args[0]]) ? $this->data[$args[0]] : null;
		}

		return $this->data;
	}

	/**
	 * Cascade data to children
	 *
	 * @param  array $args
	 */
	public function cascadeData($args = array())
    {
		if( $this->hasChildren() ) {
            if( count($args) >= 2 ) {
                $this->children()->data($args[0], $args[1]);
            } else {
                $this->children()->data($args[0]);
            }
		}
	}

	/**
	 * Check if propery exists either in the class or the meta collection
	 *
	 * @param  String  $property
	 * @return Boolean
	 */
	public function hasProperty($property)
    {
        if (property_exists($this, $property) || !is_null($this->data($property))) {
            return true;
        }

        return false;
    }


	/**
	 * Search in meta data if a property doesn't exist otherwise return the property
	 *
	 * @param  string
	 * @return string
	 */
	public function __get($prop)
    {
		if(property_exists($this, $prop)) {
			return $this->$prop;
		}
		
		return $this->data($prop);
	}

}
