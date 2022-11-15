// Memory Game
// 2014 Nate Wiley
// License -- MIT
// Adapted to MyTCG by Taty
(function()
{

	var Memory = {

		init: function( cards )
		{
			this.$game = $(".m-game");
			
			this.$restartButton = $("button.restart");
			this.cardsArray = $.merge(cards, cards);
			this.shuffleCards(this.cardsArray);
			this.setup();
		},

		shuffleCards: function( cardsArray )
		{
			this.$cards = $(this.shuffle(this.cardsArray));
		},

		setup: function()
		{
			this.html = this.buildHTML();
			this.$game.html(this.html);
			this.$memoryCards = $(".mcard");
			this.binding();
			this.paused = false;
			this.guess = null;
		},

		binding: function()
		{
			this.$memoryCards.on("click", this.cardClicked);
			this.$restartButton.on("click", $.proxy(this.reset, this));
		},

		// kinda messy but hey
		cardClicked: function()
		{
			var _ = Memory;
			var $card = $(this);
			if(!_.paused && !$card.find(".inside").hasClass("matched") && !$card.find(".inside").hasClass("picked"))
			{
				$card.find(".inside").addClass("picked");
				if(!_.guess)
				{
					_.guess = $(this).attr("data-id");
				}

				else if(_.guess == $(this).attr("data-id") && !$(this).hasClass("picked"))
				{
					$(".picked").addClass("matched");
					_.guess = null;
				}

				else
				{
					_.guess = null;
					_.paused = true;
					setTimeout(function()
					{
						$(".picked").removeClass("picked");
						Memory.paused = false;
					}, 600);
				}

				if($(".matched").length == $(".mcard").length)
				{
					_.win();
				}
			}
		},

		win: function()
		{
			this.paused = true;
			document.location="/games.php?play=memory&go=prize";
		},

		shuffle: function( array )
		{
			var counter = array.length, temp, index;
			while( counter > 0 )
			{
				index = Math.floor(Math.random() * counter);
				counter--;
				temp = array[counter];
				array[counter] = array[index];
				array[index] = temp;
			}
			return array;
		},

		buildHTML: function()
		{
			var frag = '';
			this.$cards.each(function(k, v)
			{
				frag += '<div class="mcard" data-id="'+ v.id +'"><div class="inside">\
				<div class="front"><img src="'+ v.img +'"\
				alt="'+ v.name +'" /></div>\
				<div class="back"><img src="/admin/games/images/m00.png"\
				 /></div></div>\
				</div>'; // Please change the url to your card back!
			});
			return frag;
		}
	};

	// The var below controls all cards. 
	// You must give a name so the code know which one is the pair and put the url into it. Or leave how it is.
	// Add or remove elements so you can control how many pairs you want.

	var cards = [
		{
			name: "pairone",
			img: "/admin/games/images/m01.png",
			id: 1,
		},
		{
			name: "pairtwo",
			img: "/admin/games/images/m02.png",
			id: 2
		},
		{
			name: "pairthree",
			img: "/admin/games/images/m03.png",
			id: 3
		},
		{
			name: "pairfour",
			img: "/admin/games/images/m04.png",
			id: 4
		}, 
		{
			name: "pairfive",
			img: "/admin/games/images/m05.png",
			id: 5
		},
		{
			name: "pairsix",
			img: "/admin/games/images/m06.png",
			id: 6
		},
		{
			name: "pairseven",
			img: "/admin/games/images/m07.png",
			id: 7
		},
		{
			name: "paireight",
			img: "/admin/games/images/m08.png",
			id: 8
		},
		{
			name: "pairnine",
			img: "/admin/games/images/m09.png",
			id: 9
		},
		{
			name: "pairten",
			img: "/admin/games/images/m10.png",
			id: 10
		},
	];

	Memory.init(cards);

})();
