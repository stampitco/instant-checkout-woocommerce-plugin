const Mediator = function Mediator({ topics }) {
    this.topics = topics || {};
}

Mediator.prototype.subscribe = function subscribe( topic, fn ) {

    if ( ! this.topics.hasOwnProperty( topic ) ){
        this.topics[ topic ] = [];
    }

    this.topics[ topic ].push( { context: this, callback: fn } );

    return this;
};

Mediator.prototype.publish = function publish( topic ) {

    if ( ! this.topics.hasOwnProperty( topic ) ){
        return false;
    }

    let args = Array.prototype.slice.call( arguments, 1 );

    for ( let i = 0, l = this.topics[ topic ].length; i < l; i++ ) {
        let subscription = this.topics[ topic ][ i ];
        subscription.callback.apply( subscription.context, args );
    }
    return this;
};

export default Mediator;