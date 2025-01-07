<?php

namespace PaidCommunities\WordPress\Model;

use PaidCommunities\WordPress\Util\GeneralUtils;

abstract class AbstractModelFactory implements ModelFactoryInterface {

	public function buildModel( $clazz, $response ) {
		// build the model class from the response
		$model = $clazz ? new $clazz() : new \stdClass();
		foreach ( $response as $key => $value ) {
			if ( \is_array( $value ) ) {
				if ( $this->hasClass( $key ) ) {
					$value = $this->buildModel( $this->getModelClass( $key ), $value );
				} else {
					if ( ! GeneralUtils::isList( $value ) ) {
						$value = $this->buildModel( \stdClass::class, $value );
					}
				}
			}
			if ( $model instanceof AbstractModel ) {
				$methodName = 'transform' . str_replace( '_', '', ucwords( $key, '_' ) );
				if ( method_exists( $model, $methodName ) ) {
					$value = $model->$methodName( $value );
				}
				$model->{$key} = $value;
			}
		}

		return $model;
	}

	abstract protected function getModelClass( $name );

	abstract protected function hasClass( $name );

}