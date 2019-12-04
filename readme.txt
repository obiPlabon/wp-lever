=== WP Lever ===
Contributors: obiPlabon
Tags: wp-lever, lever.co, job-listing
Requires at least: 4.5
Tested up to: 4.9
Stable tag: 1.0.1
Requires PHP: 5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Do you enlist jobs on Lever.co? Then using `[lever]` shortcode you can easily show those listed jobs on your site. It's super convenient.
Just use your registered site name with the shortcode like this `[lever site="leverdemo"]`. There are other helpful shortcode parameters.
Please check [this link](https://github.com/lever/postings-api#get-a-list-of-job-postings) and check the query parameters. All the query parameters are supported as shortcode parameters.
Please note that commitment, team and department shortcode parameters support comma separated values. Here's an example: `[lever site="leverdemo" department="Sales,Finance"]`

== Frequently Asked Questions ==

= Can I use this shortcode with any theme? =

Yes, you can use any this plugin with any theme you want.


== Configuration ===
A configuration page was added at v1.0.2, in this section we'll describe how to properly configure the plugin.

 * Api Key can be generated at [this link](https://hire.lever.co/settings/integrations?tab=api), you'll have to set a valid
 API key for the plugin to be able to send the POST request to Lever API
 * Consent Text is the text you want to appear next to the consent checkbox at the apply page, it can accept HTML
 (example: Yes, MyCompanyInc can contact me about future job opportunities for up to 2 years<br/><a href="#" target="_blank" class="link"><small>Privacy policy</small></a>
 * Additional Cards Template receives a json with the custom fields. Currently the Lever API does not provide any endpoint for receiving the custom fields per job posting
 so instead of that we can configure the custom fields for all the job postings, you can retrieve this json from the apply page hosted on lever e.g https://jobs.lever.co/leverdemo/dac7a8ff-8c1c-499e-abae-8136d471cdb6/apply
 there is a hidden input with name *cards[5743b1bc-c242-417e-b566-79da8743bd1d][baseTemplate]* you have to get the value of this input

 example json:
```json
 {
   "createdAt": 1537275235211,
   "text": "ADDITIONAL QUESTIONS",
   "instructions": "",
   "type": "posting",
   "fields": [
     {
       "type": "text",
       "text": "Earliest possible starting date:",
       "description": "",
       "required": true,
       "id": "28dc03e9-5377-4055-8cbe-39d36bb71be9"
     }
   ],
   "accountId": "122cdc2a-5075-4807-94f7-6723b0ac3656",
   "id": "bb4b968d-6594-412d-b01b-6e5baccdbbde"
 }
```

 * Silent if enabled the Lever confirmation mail won't be sent to the applicants
 * Success message can receive HTML and will be displayed after a successful job application
 example: `<h3 class="default-msg">Your application has successfully been sent.</h3>`
 * Fail message can receive HTML and will be displayed after a failed job application
 example `<h3 class="default-msg">Failed to send the application please try again later.</h3>`

== Changelog ==

= 1.0.0 =
* Initial release

= 1.0.1 =
* added shortcode parameter - filters="disabled", available options: { enabled, disabled }, default : { enabled }
* added shortcode parameter - primary-color="#E91E63"
* added shortcode parameter - primary-text-color="white"

= 1.0.2 =
* integrated the job apply & description pages
* added configuration page for the plugin (located at Settings -> WP Lever Settings)
