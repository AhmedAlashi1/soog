// sample calendar events data
'use strict'
var curYear = moment().format('YYYY');
var curMonth = moment().format('MM');


// Calendar Event Source

var azCalendarEvents = {


	id: 1,
	events:
        // $.ajax({
        //     type: "GET",
        //     url: `calenda/api`,
        //     success: (data) => {
        //         var x=data.data;
        //
        //     }
        // })
        [
        {
    start: curYear + '-' + curMonth + '-08T08:30:00',
    end: curYear + '-' + curMonth + '-10T13:00:00',
    title: 'BootstrapDash Meetup',
    backgroundColor: '#00cccc',
    borderColor: '#00cccc',
    textColor: '#fff',

}
	  ]
};
// Birthday Events Source
var azBirthdayEvents = {
	id: 2,
	backgroundColor: '#3bb001',
	borderColor: '#3bb001',
	textColor: '#fff',
	events: [{
		id: '7',
		// start: curYear + '-' + curMonth + '-05T18:00:00',
		start: "2022-06-21T10:22:12.000000Z",
		title: 'Socrates Birthday',
		backgroundColor: '#f10075',
		borderColor: '#f10075',
        updated_at : "2022-06-21T10:22:12.000000Z",
        create_at : "2022-06-21T10:22:12.000000Z",
	}
	]
};
var azHolidayEvents = {
	id: 3,
	backgroundColor: '#f10075',
	borderColor: '#f10075',
	textColor: '#fff',
	events: [

	]
};
var azOtherEvents = {
	id: 4,
	backgroundColor: '#ffb52b',
	borderColor: '#ffb52b',
	textColor: '#fff',
	events: [

	]
};
