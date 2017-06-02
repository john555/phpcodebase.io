var Composer = (function(){

	'use strict';

	var messages = [];

	function _post(){

	}

	function compose(msg){
		messages.push(msg);
	}

	function readMessages(){
		for(var i = 0; i < messages.length; i++){
			console.log(messages[i]);
		}
	}

	return {
		compose: compose,
		readMessages: readMessages
	}

})();