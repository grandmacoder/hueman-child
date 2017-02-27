<?php
/*----------------
These functions were added back into the site from an earlier version of wp_courseware
The new version of wp_courseware is using a tighter OOP approach and their functions
are creating a $this object based on the course unit the user is currently one, therefore, from custom theme 
templates that show quiz and course completion data the OOP approach in the new version of the plugin
are not compatible/accessible
------------------*/
/**
 * Render all of the correct answers for the user.
 * @param Object $quizDetails The details of the quiz to show to the user.
 * @param Object $userResults The details of the user's results (if known).
 * 
 * @param String The correct answers as HTML.
 */
function WPCW_quizzes_showAllCorrectAnswers($quizDetails, $userResults)
{
	// Hopefully not needed, but just in case.
	if (!$quizDetails) {
		return false;
	}
	
	// @since V2.90
	// Working on a survey, so this is a little different. We only show a response, not giving
	// any indication if they are right or not.
	if ('survey' == $quizDetails->quiz_type)
	{
		$setting_showCorrectAnswer = false;
		$setting_showUserAnswer    = true;
		$setting_showExplanation   = false;
		$setting_showMarkAnswers   = false;
	}

	// Working on a quiz
	else 
	{
		// Work out the settings for showing the answers 
		$setting_allRaw = maybe_unserialize($quizDetails->show_answers_settings);
		
		// Extract our settings. 
		$setting_showCorrectAnswer 	= ('on' == WPCW_arrays_getValue($setting_allRaw, 'show_correct_answer')); 
		$setting_showUserAnswer    	= ('on' == WPCW_arrays_getValue($setting_allRaw, 'show_user_answer'));
		$setting_showExplanation    = ('on' == WPCW_arrays_getValue($setting_allRaw, 'show_explanation'));
		$setting_showMarkAnswers    = ('on' == WPCW_arrays_getValue($setting_allRaw, 'mark_answers'));
	}

	
	// Create a simple DIV wrapper for the correct answers.
	$html = '<div class="wpcw_fe_quiz_box_wrap wpcw_fe_quiz_box_full_answers">';
			
		$html .= '<div class="wpcw_fe_quiz_box wpcw_fe_quiz_box_pending">';
		
			// #### 1 - Quiz Title - constant for all quizzes
			$html .= sprintf('<div class="wpcw_fe_quiz_title"><b>%s</b> %s</div>', __('Correct Answers for: ', 'wp_courseware'), $quizDetails->quiz_title);
			
			// #### 2 - Header before questions
			$html .= '<div class="wpcw_fe_quiz_q_hdr"></div>';			
			
			// #### 3 - Extract the correct answer from the index of questions.
			if ($quizDetails->questions && count($quizDetails->questions) > 0)
			{
				$questionNum = 1;
				
				foreach ($quizDetails->questions as $question) 
				{
					$html .= '<div class="wpcw_fe_quiz_q_single">';
	
					// ### 3a - Question title
					$html .= sprintf('<div class="wpcw_fe_quiz_q_title">%d.  %s</div>', 
						$questionNum++,
						$question->question_question
					);
					
					// ### 3b - Their Mark (correct or incorrect)
					if ($setting_showMarkAnswers)
					{		
						// See if they have an answer for this question first
						if (!empty($userResults->quiz_data) && isset($userResults->quiz_data[$question->question_id]))
						{
							$theirAnswerDetails = $userResults->quiz_data[$question->question_id];
							
							switch ($question->question_type)
							{
								// open-ended questions have grades.
								case 'open':
								case 'upload':
										// Check if question still needs to be marked.
										if (isset($userResults->quiz_needs_marking_list) && is_array($userResults->quiz_needs_marking_list) && in_array($question->question_id, $userResults->quiz_needs_marking_list))
										{
											$html .= sprintf('<div class="wpcw_fe_quiz_q_result wpcw_fe_quiz_q_user_grade"><b>%s:</b>&nbsp;&nbsp;%s</div>', 
												__('Your Grade', 'wp_courseware'),
												__('Pending', 'wp_courseware')
											);
										}

										// Nope, it's marked, so show the grade.
										else 
										{
											$gradePercentage = WPCW_arrays_getValue($theirAnswerDetails, 'their_grade');
											
											$html .= sprintf('<div class="wpcw_fe_quiz_q_result wpcw_fe_quiz_q_user_grade"><b>%s:</b>&nbsp;&nbsp;%d%%</div>', 
												__('Your Grade', 'wp_courseware'),
												$gradePercentage
											);
										}
									break;
									
								case 'multi':
								case 'truefalse':
										// Got it right...
										if ('yes' == WPCW_arrays_getValue($theirAnswerDetails, 'got_right'))
										{
											$html .= sprintf('<div class="wpcw_fe_quiz_q_result wpcw_fe_quiz_q_result_correct">%s</div>', __('Correct ', 'wp_courseware'));
										}
										
										// Got it wrong...
										else {
											$html .= sprintf('<div class="wpcw_fe_quiz_q_result wpcw_fe_quiz_q_result_incorrect">%s</div>', __('Incorrect ', 'wp_courseware'));
										}
									break;
							}

						}
					} // end if ($setting_showMarkAnswers)			
					
					
					// Work out the correct answer
					$correctAnswer = WPCW_quizzes_getCorrectAnswer($question);
			               
					
					// ### 3c - Answer - User's Answer
					if ($setting_showUserAnswer)
					{
						$theirAnswer = false;
						
						// See if they have an answer for this question first
						if (!empty($userResults->quiz_data) && isset($userResults->quiz_data[$question->question_id]))
						{
						
							$theirAnswerDetails = $userResults->quiz_data[$question->question_id];
							
							// Handle file types and open-ended questions
							switch($question->question_type)
							{
								// File upload, so show link to file.
								case 'upload':
										$theirAnswerRaw = WPCW_arrays_getValue($theirAnswerDetails, 'their_answer');
										$theirAnswer = sprintf('<a href="%s%s" target="_blank">%s .%s %s (%s)</a>', 
											WP_CONTENT_URL, $theirAnswerRaw,
											__('Open', 'wp_courseware'),
											pathinfo($theirAnswerRaw, PATHINFO_EXTENSION),
											__('File', 'wp_courseware'), 
											WPCW_files_getFileSize_human($theirAnswerRaw)								
										);
									break;
									
								// Paragraph of text - show with <p> tags
								case 'open':
										$theirAnswer = wpautop(WPCW_arrays_getValue($theirAnswerDetails, 'their_answer'));
									break;
									
								default:
										$theirAnswer = WPCW_arrays_getValue($theirAnswerDetails, 'their_answer');
									break;
							}							
						}
					
						
						if ($theirAnswer)
						{
							$html .= sprintf('<div class="wpcw_fe_quiz_q_your_answer"><b>%s:</b>&nbsp;&nbsp;%s</div>', 
								__('Your Answer', 'wp_courseware'),
								$theirAnswer
							);
						}
						
						// We don't have an answer for this question.
						else 
						{
							$html .= sprintf('<div class="wpcw_fe_quiz_q_your_answer wpcw_fe_quiz_q_your_answer_none_found"><b>%s:</b>&nbsp;&nbsp;(%s)</div>', 
								__('Your Answer', 'wp_courseware'),
								__('We don\'t have your answer for this question', 'wp_courseware')
							);
						}
					} // end if ($setting_showUserAnswer)

					
					// ### 3c - Answer - The Correct Answer
					if ($setting_showCorrectAnswer && $correctAnswer)
					{						
						$html .= sprintf('<div class="wpcw_fe_quiz_q_correct"><b>%s:</b>&nbsp;&nbsp;%s</div>', 
							__('Correct Answer', 'wp_courseware'),
							$correctAnswer
						);
					} // end if ($setting_showCorrectAnswer && $correctAnswer)
					
					
					
					// Quiz Explanation - If there's a quiz explanation, put it here.
					if ($setting_showExplanation && $question->question_answer_explanation) 
					{
						$html .= sprintf('<div class="wpcw_fe_quiz_q_explanation"><b>%s:</b>&nbsp;&nbsp;%s</div>', 
							__('Explanation', 'wp_courseware'),
							$question->question_answer_explanation
						);
					}
					
					$html .= '</div>'; // wpcw_fe_quiz_q_single
				}				 
			} 			
				
		$html .= '</div>'; // .wpcw_fe_quiz_box 
	$html .= '</div>'; // .wpcw_fe_quiz_box_wrap
		
	return $html;
}

/**
 * Get the correct answer for a question.
 * 
 * @param Object $question The question object.
 * @param Mixed $providedAnswer If specified, use this to specify the correct answer. Otherwise use the correct answer for the question.
 * 
 * @return String The answer for the question.
 */
function WPCW_quizzes_getCorrectAnswer($question, $providedAnswer = false)
{
	$correctAnswer = false;
	if (!$providedAnswer) {
		$providedAnswer = $question->question_correct_answer;
	}
	
	switch ($question->question_type)
	{
		// Just use true or false if a t/f question
		case 'truefalse':
			if ('true' == $providedAnswer) {
				$correctAnswer = __('True', 'wp_courseware');	
			} else {
				$correctAnswer = __('False', 'wp_courseware');
			}
			break;
			
		// Multiple choice question - so turn list of answers into array
		// then use 1-indexing to get the text of the result.
		case 'multi':
				$answerListRaw = WPCW_quizzes_decodeAnswers($question->question_data_answers);
				$answerIdx = $providedAnswer;
								
				// Just double check that the selected answer is valid.
				// 2013-12-06 - Added <= rather than < count for the final answer to accept the final answer.
				// Because it's 1-indexed, not 0 indexed.
				if ($answerIdx >= 0 && $answerIdx <= count($answerListRaw) && isset($answerListRaw[$answerIdx])) {
					$correctAnswer = $answerListRaw[$answerIdx]['answer'];				
				}
			break;
	}
	
	return $correctAnswer;
}

/**
 * Checks all of the answers against the list of questions and return which answers are right or wrong.
 * 
 * @param Object $quizDetails The quiz details to check.
 * @param Array $checkedAnswerList The answers to check.
 * 
 * @return Array Lists of the correct and wrong answers. (correct => Array, wrong => Array, 'needs_marking' => Array)
 */
function WPCW_quizzes_checkForCorrectAnswers($quizDetails, $checkedAnswerList)
{
	$resultDetails = array(
		'correct'		=> array(),
		'wrong'			=> array(),
		'needs_marking' => array(),
	); 
	
	// Run through questions, and check each one for correctness.
	foreach ($quizDetails->questions as $questionID => $question)
	{
		// Got to check the question type, as can't automatically score the open-ended question types.
		switch ($question->question_type)
		{
			case 'truefalse':
			case 'multi':
					// If the answer is correct, add the question and answer to the correct list;
					if ($checkedAnswerList[$questionID] == $question->question_correct_answer) {
						$resultDetails['correct'][$questionID] = $checkedAnswerList[$questionID];
					} 
					// Answer is wrong, so add to wrong list.
					else {
						$resultDetails['wrong'][$questionID] = $checkedAnswerList[$questionID];
					}				
				break;
				
			// Uploaded files and open-ended questions need marking
			case 'upload':
			case 'open':
					$resultDetails['needs_marking'][$questionID] = $checkedAnswerList[$questionID];
				break;
		}
	}
	
	return $resultDetails;
}
?>