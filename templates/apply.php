<div class="lever">
    <div class="lever-apply-page" id="<?php echo esc_attr( $job->get_id() ); ?>">
        <div class="lever-job-header">
            <div>
                <h4 class="lever-job-title">
					<?php echo esc_html( $job->get_title() ); ?>
                </h4>
                <div>
                    <ul class="lever-categories">
						<?php
						$location   = $job->get_categories()->get_location();
						$department = $job->get_categories()->get_department();
						$team       = $job->get_categories()->get_team();
						$commitment = $job->get_categories()->get_commitment();
						?>
						<?php if ( ! empty( $location ) ): ?>
                            <li><?php echo esc_html( $location ); ?></li>
						<?php endif; ?>
						<?php if ( ! empty( $department ) ): ?>
                            <li><?php echo esc_html( $department ); ?></li>
						<?php endif; ?>
						<?php if ( ! empty( $team ) ): ?>
                            <li><?php echo esc_html( $team ); ?></li>
						<?php endif; ?>
						<?php if ( ! empty( $commitment ) ): ?>
                            <li><?php echo esc_html( $commitment ); ?></li>
						<?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="lever-apply-form">
            <form enctype="multipart/form-data" method="POST">
                <div class="lever-apply-section">
                    <h4>Submit your application</h4>
                    <ul>
                        <li class="application-question">
                            <label for="resume-upload-input">Resume/CV</label>
                            <div class="field">
                                <div class="lever-file-input">
                                    <svg class="icon icon-paperclip" x="0px" y="0px" width="16px" height="16"
                                         viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve">
	                                    <path
                                                fill="#3F484B"
                                                d="M3.036,14.696c-0.614,0-1.219-0.284-1.788-0.853l-0.083-0.082c-0.586-0.578-2.305-2.956,0.008-5.391 c1.165-1.226,2.771-2.813,4.471-4.493C6.558,2.976,7.509,2.036,8.455,1.09c1.708-1.707,2.958-1.317,4.894,0.528 c2.288,2.178,2.707,4.322,1.718,5.463c-1.314,1.515-6.285,6.488-6.496,6.699c-0.278,0.279-0.729,0.279-1.008,0 c-0.278-0.278-0.278-0.729,0-1.008c0.051-0.051,5.146-5.148,6.427-6.625c0.294-0.339,0.339-1.629-1.624-3.498 c-1.13-1.076-1.465-1.989-2.902-0.551c-0.948,0.948-1.901,1.89-2.817,2.793C4.954,6.564,3.355,8.144,2.207,9.353 c-1.349,1.421-0.656,2.788-0.041,3.395l0.089,0.088c0.524,0.523,0.952,0.665,1.718-0.102c0.213-0.213,0.656-0.644,1.213-1.185 C6.729,10.05,9.6,7.26,10.18,6.534c0.184-0.23,0.452-0.787,0.196-1.011c-0.353-0.31-1.002,0.315-1.192,0.514 c-2.012,2.112-4.64,4.643-4.666,4.667c-0.283,0.273-0.734,0.265-1.007-0.02c-0.273-0.284-0.265-0.734,0.019-1.007 c0.026-0.025,2.633-2.535,4.622-4.624c1.291-1.356,2.48-1.201,3.162-0.604c0.832,0.727,0.636,2.154-0.021,2.974 c-0.586,0.734-2.847,2.945-5.113,5.146c-0.55,0.536-0.988,0.96-1.199,1.171C4.346,14.378,3.686,14.696,3.036,14.696L3.036,14.696z">
                                        </path>
                                    </svg>
                                    <span class="filename"></span>
                                    <span class="default-label">ATTACH RESUME/CV</span>
                                    <input type="file" name="lever_resume" id="resume-upload-input"/>
                                </div>
                            </div>
                        </li>
                        <li class="application-question">
                            <label for="name">Full name<span class="required">✱</span></label>
                            <div class="field">
                                <input id="name" type="text" name="lever_name" required="">
                            </div>
                        </li>

                        <li class="application-question">
                            <label for="email">Email<span class="required">✱</span></label>
                            <div class="field">
                                <input name="lever_email" type="email"
                                       pattern="[a-zA-Z0-9.#$%&amp;'*+\/=?^_`{|}~][a-zA-Z0-9.!#$%&amp;'*+\/=?^_`{|}~-]*@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*"
                                       required="">
                            </div>
                        </li>

                        <li class="application-question">
                            <label for="phone">Phone<span class="required">✱</span></label>
                            <div class="field">
                                <input id="phone" type="text" name="lever_phone" required="">
                            </div>
                        </li>
                        <li class="application-question">
                            <label for="org">Current Company</label>
                            <div class="field">
                                <input id="org" type="text" name="lever_org">
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="lever-apply-section">
                    <h4>Links</h4>
                    <ul>
                        <li class="application-question">
                            <label for="social">Xing / LinkedIn URL</label>
                            <div class="field">
                                <input id="social" type="text" name="lever_urls[Xing / LinkedIn]">
                            </div>
                        </li>
                        <li class="application-question">
                            <label for="github">GitHub URL</label>
                            <div class="field">
                                <input id="github" type="text" name="lever_urls[GitHub]">
                            </div>
                        </li>
                        <li class="application-question">
                            <label for="portfolio">Portfolio URL</label>
                            <div class="field">
                                <input id="portfolio" type="text" name="lever_urls[Portfolio]">
                            </div>
                        </li>
                        <li class="application-question">
                            <label for="other">Other website</label>
                            <div class="field">
                                <input id="other" type="text" name="lever_urls[Other]">
                            </div>
                        </li>
                    </ul>
                </div>

				<?php include 'parts/apply/additional_cards.php'; ?>

                <div class="lever-apply-section">
                    <h4>Additional information</h4>
                    <div class="application-question">
                        <textarea placeholder="Add a cover letter or anything else you want to share."
                                  name="lever_comments"></textarea>
                    </div>
                </div>

                <input type="hidden" name="lever_accountId" value="4ddaacba-f075-4807-94f7-6958b0ac3656">
                <input type="hidden" name="lever_referer"
                       value="<?php echo $referer; ?>">

                <div class="lever-apply-section consent-section">
                    <label>
                        <input id="consent" type="checkbox" name="lever_consent[marketing]" value="true">
                        <p class="application-answer-alternative">
							<?php echo get_option( 'consent_text', '' ); ?>
                        </p>
                    </label>
                </div>

                <div class="lever-job-action text-center">
                    <input type="submit"
                           class="lever-job-apply action-lg"
                           value="<?php esc_html_e( 'Submit application', 'wp-lever' ); ?>"/>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'parts/style-overwrites.php';
