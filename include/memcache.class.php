<?php
	/**
	 * \ingroup Cache
	 *
	 * Support for caching via memcached
	 */
    class MemCache1
    {
		var $__memcache_hits = 0;
		var $__memcache_misses = 0;
		var $__memcache_queries = 0;

        var $cache;
        var $lifeTime;

        var $_disabledCacheCategories = array();

        function __construct()
        {
        	global $memcache_config;
			$this->cache = new Memcache;
			foreach($memcache_config['SERVER'] as $server)
			{
				$this->cache->addServer($server['HOST'], $server['PORT']);
			}
//
//			if (@$this->cache->connect($memcache_config["HOST"], $memcache_config['PORT']))
//			{
//				//echo "mem cache init ok";
//			}
//			else
//			{
//				//echo "mem cache init failed";
//				$this->cache = false;
//			}
			$this->lifeTime = '86400';
        }

		function setLifeTime( $lifeTime )
		{
			$this->lifeTime = $lifeTime;
		}

        function setData( $id, $group, $data ,$lifeTime = false)
        {
			$key = $this->getKey( $id, $group );
			if (!$lifeTime)
				$lifeTime = $this->lifeTime;
            return $this->cache->set( $key, $data,0, $lifeTime );
        }
		
		/** 
		 * Works in the same way as Cache::setData does, but instead of setting single values,
		 * it assumes that the value we're setting for the given key is part of an array of values. This
		 * method is useful for data which we know is not unique.
		 */
		function setMultipleData( $id, $group, $data )
		{
			$currentData = $this->getData( $id, $group );
			if( !$currentData ) $currentData = Array();

			/**
			* :TODO:
			* It's clear that we're only going to cache DbObjects using this method
			* but what happens if we don't? Should we force developers to provide a method
			* to uniquely identify their own objects? We definitely need a unique id here so that
			* the array doesn't grow forever...
			*/
			$currentData[$data->getId()] = $data;

			return $this->setData( $id, "$group", $currentData );
		}

        function getData( $id, $group )
        {
			global $__memcache_hits;			
			global $__memcache_queries;
			global $__memcache_misses;		
		
			$__memcache_queries++;

			$key = $this->getKey( $id, $group );
			$data = $this->cache->get( $key );

			if ($data) {
				$__memcache_hits++;
			}
			else {
				$__memcache_misses++;						
			}

			return $data;
        }

        function removeData( $id, $group )
        {
			$key = $this->getKey( $id, $group );
			return $this->cache->delete( $key);
        }

        function clearCacheByGroup( $group )
        {
            return true;
        }

        function clearCache()
        {
            //return $this->cache->flush_all();
			return $this->cache->flush();
        }

		/**
		 * returns the total count of cache hits, miss and total queries over the lifetime of the
		 * script so far.
		 *
		 * @return An array with 3 keys: "hits", "total" and "miss"
		 */
		function getCacheStats()
		{
			global $__memcache_hits;
			global $__memcache_misses;
			global $__memcache_queries;
		
			return( Array( "total" => $__memcache_queries,
			               "hits"  => $__memcache_hits,
						   "miss"  => $__memcache_misses )); 
		}
		
		function getKey( $id, $group )
		{
			return $group.':'.$id;	
		}
		
		function all_getExtendedStats()
		{
			return $this->cache->getExtendedStats();
		}
    }
?>
