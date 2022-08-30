<textarea id="<?=$name;?>" name="<?=$name;?>" class="codi">
<?=str_replace('</textarea>','&lt;/textarea>',htmlspecialchars($file));?>
</textarea>
<textarea class="code-none">
<?=str_replace('</textarea>','&lt;/textarea>',htmlspecialchars($file));?>
</textarea>
<br><br>
<script>
	 function methods_php(cm, pred) {
           var cur = cm.getCursor();
        if (!pred || pred()) setTimeout(function() {
          if (!cm.state.completionActive)
            cm.showHint({completeSingle: false});
        }, 100);
           var orig = CodeMirror.hint.javascript;
            const list=[""];
            const func_php = ["zend_version","func_num_args","func_get_arg","func_get_args","strlen","strcmp","strncmp","strcasecmp","strncasecmp","error_reporting","define","defined","get_class","get_called_class","get_parent_class","is_subclass_of","is_a","get_class_vars","get_object_vars","get_mangled_object_vars","get_class_methods","method_exists","property_exists","class_exists","interface_exists","trait_exists","function_exists","class_alias","get_included_files","get_required_files","trigger_error","user_error","set_error_handler","restore_error_handler","set_exception_handler","restore_exception_handler","get_declared_classes","get_declared_traits","get_declared_interfaces","get_defined_functions","get_defined_vars","get_resource_type","get_resource_id","get_resources","get_loaded_extensions","get_defined_constants","debug_backtrace","debug_print_backtrace","extension_loaded","get_extension_funcs","gc_mem_caches","gc_collect_cycles","gc_enabled","gc_enable","gc_disable","gc_status","strtotime","date","idate","gmdate","mktime","gmmktime","checkdate","strftime","gmstrftime","time","localtime","getdate","date_create","date_create_immutable","date_create_from_format","date_create_immutable_from_format","date_parse","date_parse_from_format","date_get_last_errors","date_format","date_modify","date_add","date_sub","date_timezone_get","date_timezone_set","date_offset_get","date_diff","date_time_set","date_date_set","date_isodate_set","date_timestamp_set","date_timestamp_get","timezone_open","timezone_name_get","timezone_name_from_abbr","timezone_offset_get","timezone_transitions_get","timezone_location_get","timezone_identifiers_list","timezone_abbreviations_list","timezone_version_get","date_interval_create_from_date_string","date_interval_format","date_default_timezone_set","date_default_timezone_get","date_sunrise","date_sunset","date_sun_info","libxml_set_streams_context","libxml_use_internal_errors","libxml_get_last_error","libxml_get_errors","libxml_clear_errors","libxml_disable_entity_loader","libxml_set_external_entity_loader","openssl_x509_export_to_file","openssl_x509_export","openssl_x509_fingerprint","openssl_x509_check_private_key","openssl_x509_verify","openssl_x509_parse","openssl_x509_checkpurpose","openssl_x509_read","openssl_x509_free","openssl_pkcs12_export_to_file","openssl_pkcs12_export","openssl_pkcs12_read","openssl_csr_export_to_file","openssl_csr_export","openssl_csr_sign","openssl_csr_new","openssl_csr_get_subject","openssl_csr_get_public_key","openssl_pkey_new","openssl_pkey_export_to_file","openssl_pkey_export","openssl_pkey_get_public","openssl_get_publickey","openssl_pkey_free","openssl_free_key","openssl_pkey_get_private","openssl_get_privatekey","openssl_pkey_get_details","openssl_pbkdf2","openssl_pkcs7_verify","openssl_pkcs7_encrypt","openssl_pkcs7_sign","openssl_pkcs7_decrypt","openssl_pkcs7_read","openssl_cms_verify","openssl_cms_encrypt","openssl_cms_sign","openssl_cms_decrypt","openssl_cms_read","openssl_private_encrypt","openssl_private_decrypt","openssl_public_encrypt","openssl_public_decrypt","openssl_error_string","openssl_sign","openssl_verify","openssl_seal","openssl_open","openssl_get_md_methods","openssl_get_cipher_methods","openssl_get_curve_names","openssl_digest","openssl_encrypt","openssl_decrypt","openssl_cipher_iv_length","openssl_dh_compute_key","openssl_pkey_derive","openssl_random_pseudo_bytes","openssl_spki_new","openssl_spki_verify","openssl_spki_export","openssl_spki_export_challenge","openssl_get_cert_locations","preg_match","preg_match_all","preg_replace","preg_filter","preg_replace_callback","preg_replace_callback_array","preg_split","preg_quote","preg_grep","preg_last_error","preg_last_error_msg","ob_gzhandler","zlib_get_coding_type","gzfile","gzopen","readgzfile","zlib_encode","zlib_decode","gzdeflate","gzencode","gzcompress","gzinflate","gzdecode","gzuncompress","gzwrite","gzputs","gzrewind","gzclose","gzeof","gzgetc","gzpassthru","gzseek","gztell","gzread","gzgets","deflate_init","deflate_add","inflate_init","inflate_add","inflate_get_status","inflate_get_read_len","bzopen","bzread","bzwrite","bzflush","bzclose","bzerrno","bzerrstr","bzerror","bzcompress","bzdecompress","cal_days_in_month","cal_from_jd","cal_info","cal_to_jd","easter_date","easter_days","frenchtojd","gregoriantojd","jddayofweek","jdmonthname","jdtofrench","jdtogregorian","jdtojewish","jdtojulian","jdtounix","jewishtojd","juliantojd","unixtojd","ctype_alnum","ctype_alpha","ctype_cntrl","ctype_digit","ctype_lower","ctype_graph","ctype_print","ctype_punct","ctype_space","ctype_upper","ctype_xdigit","exif_tagname","exif_read_data","exif_thumbnail","exif_imagetype","finfo_open","finfo_close","finfo_set_flags","finfo_file","finfo_buffer","mime_content_type","filter_has_var","filter_input","filter_var","filter_input_array","filter_var_array","filter_list","filter_id","ftp_connect","ftp_ssl_connect","ftp_login","ftp_pwd","ftp_cdup","ftp_chdir","ftp_exec","ftp_raw","ftp_mkdir","ftp_rmdir","ftp_chmod","ftp_alloc","ftp_nlist","ftp_rawlist","ftp_mlsd","ftp_systype","ftp_fget","ftp_nb_fget","ftp_pasv","ftp_get","ftp_nb_get","ftp_nb_continue","ftp_fput","ftp_nb_fput","ftp_put","ftp_append","ftp_nb_put","ftp_size","ftp_mdtm","ftp_rename","ftp_delete","ftp_site","ftp_close","ftp_quit","ftp_set_option","ftp_get_option","textdomain","gettext","_","dgettext","dcgettext","bindtextdomain","ngettext","dngettext","dcngettext","bind_textdomain_codeset","gmp_init","gmp_import","gmp_export","gmp_intval","gmp_strval","gmp_add","gmp_sub","gmp_mul","gmp_div_qr","gmp_div_q","gmp_div_r","gmp_div","gmp_mod","gmp_divexact","gmp_neg","gmp_abs","gmp_fact","gmp_sqrt","gmp_sqrtrem","gmp_root","gmp_rootrem","gmp_pow","gmp_powm","gmp_perfect_square","gmp_perfect_power","gmp_prob_prime","gmp_gcd","gmp_gcdext","gmp_lcm","gmp_invert","gmp_jacobi","gmp_legendre","gmp_kronecker","gmp_cmp","gmp_sign","gmp_random_seed","gmp_random_bits","gmp_random_range","gmp_and","gmp_or","gmp_com","gmp_xor","gmp_setbit","gmp_clrbit","gmp_testbit","gmp_scan0","gmp_scan1","gmp_popcount","gmp_hamdist","gmp_nextprime","gmp_binomial","hash","hash_file","hash_hmac","hash_hmac_file","hash_init","hash_update","hash_update_stream","hash_update_file","hash_final","hash_copy","hash_algos","hash_hmac_algos","hash_pbkdf2","hash_equals","hash_hkdf","mhash_get_block_size","mhash_get_hash_name","mhash_keygen_s2k","mhash_count","mhash","iconv_strlen","iconv_substr","iconv_strpos","iconv_strrpos","iconv_mime_encode","iconv_mime_decode","iconv_mime_decode_headers","iconv","iconv_set_encoding","iconv_get_encoding","json_encode","json_decode","json_last_error","json_last_error_msg","pcntl_signal_get_handler","pcntl_wifcontinued","pcntl_errno","pcntl_async_signals","pcntl_unshare","readline","readline_info","readline_add_history","readline_clear_history","readline_list_history","readline_read_history","readline_write_history","readline_completion_function","readline_callback_handler_install","readline_callback_read_char","readline_callback_handler_remove","readline_redisplay","readline_on_new_line","session_name","session_module_name","session_save_path","session_id","session_create_id","session_regenerate_id","session_decode","session_encode","session_destroy","session_unset","session_gc","session_get_cookie_params","session_write_close","session_abort","session_reset","session_status","session_register_shutdown","session_commit","session_set_save_handler","session_cache_limiter","session_cache_expire","session_set_cookie_params","session_start","shmop_open","shmop_read","shmop_close","shmop_size","shmop_write","shmop_delete","simplexml_load_file","simplexml_load_string","simplexml_import_dom","socket_select","socket_create_listen","socket_accept","socket_set_nonblock","socket_set_block","socket_listen","socket_close","socket_write","socket_read","socket_getsockname","socket_getpeername","socket_create","socket_connect","socket_strerror","socket_bind","socket_recv","socket_send","socket_recvfrom","socket_sendto","socket_get_option","socket_getopt","socket_set_option","socket_setopt","socket_create_pair","socket_shutdown","socket_last_error","socket_clear_error","socket_import_stream","socket_export_stream","socket_sendmsg","socket_recvmsg","socket_cmsg_space","socket_addrinfo_lookup","socket_addrinfo_connect","socket_addrinfo_bind","socket_addrinfo_explain","class_implements","class_parents","class_uses","spl_autoload","spl_autoload_call","spl_autoload_extensions","spl_autoload_functions","spl_autoload_register","spl_autoload_unregister","spl_classes","spl_object_hash","spl_object_id","iterator_apply","iterator_count","iterator_to_array","set_time_limit","header_register_callback","ob_start","ob_flush","ob_clean","ob_end_flush","ob_end_clean","ob_get_flush","ob_get_clean","ob_get_contents","ob_get_level","ob_get_length","ob_list_handlers","ob_get_status","ob_implicit_flush","output_reset_rewrite_vars","output_add_rewrite_var","stream_wrapper_register","stream_register_wrapper","stream_wrapper_unregister","stream_wrapper_restore","array_push","krsort","ksort","count","sizeof","natsort","natcasesort","asort","arsort","sort","rsort","usort","uasort","uksort","end","prev","next","reset","current","pos","key","min","max","array_walk","array_walk_recursive","in_array","array_search","extract","compact","array_fill","array_fill_keys","range","shuffle","array_pop","array_shift","array_unshift","array_splice","array_slice","array_merge","array_merge_recursive","array_replace","array_replace_recursive","array_keys","array_key_first","array_key_last","array_values","array_count_values","array_column","array_reverse","array_pad","array_flip","array_change_key_case","array_unique","array_intersect_key","array_intersect_ukey","array_intersect","array_uintersect","array_intersect_assoc","array_uintersect_assoc","array_intersect_uassoc","array_uintersect_uassoc","array_diff_key","array_diff_ukey","array_diff","array_udiff","array_diff_assoc","array_diff_uassoc","array_udiff_assoc","array_udiff_uassoc","array_multisort","array_rand","array_sum","array_product","array_reduce","array_filter","array_map","array_key_exists","key_exists","array_chunk","array_combine","base64_encode","base64_decode","constant","ip2long","long2ip","getenv","putenv","getopt","flush","sleep","usleep","time_nanosleep","time_sleep_until","get_current_user","get_cfg_var","error_log","error_get_last","error_clear_last","call_user_func","call_user_func_array","forward_static_call","forward_static_call_array","register_shutdown_function","highlight_file","show_source","php_strip_whitespace","highlight_string","ini_get","ini_get_all","ini_set","ini_alter","ini_restore","set_include_path","get_include_path","print_r","connection_aborted","connection_status","ignore_user_abort","getservbyname","getservbyport","getprotobyname","getprotobynumber","register_tick_function","unregister_tick_function","is_uploaded_file","move_uploaded_file","parse_ini_file","parse_ini_string","sys_getloadavg","get_browser","crc32","crypt","strptime","gethostname","gethostbyaddr","gethostbyname","gethostbynamel","dns_check_record","checkdnsrr","dns_get_record","dns_get_mx","getmxrr","net_get_interfaces","ftok","hrtime","lcg_value","md5","md5_file","getmyuid","getmygid","getmypid","getmyinode","getlastmod","sha1","sha1_file","openlog","closelog","syslog","inet_ntop","inet_pton","metaphone","header","header_remove","setrawcookie","setcookie","http_response_code","headers_sent","headers_list","htmlspecialchars","htmlspecialchars_decode","html_entity_decode","htmlentities","get_html_translation_table","assert","assert_options","bin2hex","hex2bin","strspn","strcspn","nl_langinfo","strcoll","trim","rtrim","chop","ltrim","wordwrap","explode","implode","join","strtok","strtoupper","strtolower","basename","dirname","pathinfo","stristr","strstr","strchr","strpos","stripos","strrpos","strripos","strrchr","str_contains","str_starts_with","str_ends_with","chunk_split","substr","substr_replace","quotemeta","ord","chr","ucfirst","lcfirst","ucwords","strtr","strrev","similar_text","addcslashes","addslashes","stripcslashes","stripslashes","str_replace","str_ireplace","hebrev","nl2br","strip_tags","setlocale","parse_str","str_getcsv","str_repeat","count_chars","strnatcmp","localeconv","strnatcasecmp","substr_count","str_pad","sscanf","str_rot13","str_shuffle","str_word_count","str_split","strpbrk","substr_compare","utf8_encode","utf8_decode","opendir","getdir","dir","closedir","chdir","chroot","getcwd","rewinddir","readdir","scandir","glob","exec","system","passthru","escapeshellcmd","escapeshellarg","shell_exec","proc_nice","flock","get_meta_tags","pclose","popen","readfile","rewind","rmdir","umask","fclose","feof","fgetc","fgets","fread","fopen","fscanf","fpassthru","ftruncate","fstat","fseek","ftell","fflush","fwrite","fputs","mkdir","rename","copy","tempnam","tmpfile","file","file_get_contents","unlink","file_put_contents","fputcsv","fgetcsv","realpath","fnmatch","sys_get_temp_dir","fileatime","filectime","filegroup","fileinode","filemtime","fileowner","fileperms","filesize","filetype","file_exists","is_writable","is_writeable","is_readable","is_executable","is_file","is_dir","is_link","stat","lstat","chown","chgrp","lchown","lchgrp","chmod","touch","clearstatcache","disk_total_space","disk_free_space","diskfreespace","realpath_cache_get","realpath_cache_size","sprintf","printf","vprintf","vsprintf","fprintf","vfprintf","fsockopen","pfsockopen","http_build_query","image_type_to_mime_type","image_type_to_extension","getimagesize","getimagesizefromstring","phpinfo","phpversion","phpcredits","php_sapi_name","php_uname","php_ini_scanned_files","php_ini_loaded_file","iptcembed","iptcparse","levenshtein","readlink","linkinfo","symlink","link","mail","abs","ceil","floor","round","sin","cos","tan","asin","acos","atan","atanh","atan2","sinh","cosh","tanh","asinh","acosh","expm1","log1p","pi","is_finite","is_nan","intdiv","is_infinite","pow","exp","log","log10","sqrt","hypot","deg2rad","rad2deg","bindec","hexdec","octdec","decbin","decoct","dechex","base_convert","number_format","fmod","fdiv","microtime","gettimeofday","getrusage","pack","unpack","password_get_info","password_hash","password_needs_rehash","password_verify","password_algos","proc_open","proc_close","proc_terminate","proc_get_status","quoted_printable_decode","quoted_printable_encode","mt_srand","srand","rand","mt_rand","mt_getrandmax","getrandmax","random_bytes","random_int","soundex","stream_select","stream_context_create","stream_context_set_params","stream_context_get_params","stream_context_set_option","stream_context_get_options","stream_context_get_default","stream_context_set_default","stream_filter_prepend","stream_filter_append","stream_filter_remove","stream_socket_client","stream_socket_server","stream_socket_accept","stream_socket_get_name","stream_socket_recvfrom","stream_socket_sendto","stream_socket_enable_crypto","stream_socket_shutdown","stream_socket_pair","stream_copy_to_stream","stream_get_contents","stream_supports_lock","stream_set_write_buffer","set_file_buffer","stream_set_read_buffer","stream_set_blocking","socket_set_blocking","stream_get_meta_data","socket_get_status","stream_get_line","stream_resolve_include_path","stream_get_wrappers","stream_get_transports","stream_is_local","stream_isatty","stream_set_chunk_size","stream_set_timeout","socket_set_timeout","gettype","get_debug_type","settype","intval","floatval","doubleval","boolval","strval","is_null","is_resource","is_bool","is_int","is_integer","is_long","is_float","is_double","is_numeric","is_string","is_array","is_object","is_scalar","is_callable","is_iterable","is_countable","uniqid","parse_url","urlencode","urldecode","rawurlencode","rawurldecode","get_headers","stream_bucket_make_writeable","stream_bucket_prepend","stream_bucket_append","stream_bucket_new","stream_get_filters","stream_filter_register","convert_uuencode","convert_uudecode","var_dump","var_export","debug_zval_dump","serialize","unserialize","memory_get_usage","memory_get_peak_usage","version_compare","token_get_all","token_name","xml_parser_create","xml_parser_create_ns","xml_set_object","xml_set_element_handler","xml_set_character_data_handler","xml_set_processing_instruction_handler","xml_set_default_handler","xml_set_unparsed_entity_decl_handler","xml_set_notation_decl_handler","xml_set_external_entity_ref_handler","xml_set_start_namespace_decl_handler","xml_set_end_namespace_decl_handler","xml_parse","xml_parse_into_struct","xml_get_error_code","xml_error_string","xml_get_current_line_number","xml_get_current_column_number","xml_get_current_byte_index","xml_parser_free","xml_parser_set_option","xml_parser_get_option","apache_child_terminate","apache_request_headers","getallheaders","apache_response_headers","\PDO_drivers","curl_close","curl_copy_handle","curl_errno","curl_error","curl_escape","curl_unescape","curl_multi_setopt","curl_exec","curl_file_create","curl_getinfo","curl_init","curl_multi_add_handle","curl_multi_close","curl_multi_errno","curl_multi_exec","curl_multi_getcontent","curl_multi_info_read","curl_multi_init","curl_multi_remove_handle","curl_multi_select","curl_multi_strerror","curl_pause","curl_reset","curl_setopt_array","curl_setopt","curl_share_close","curl_share_errno","curl_share_init","curl_share_setopt","curl_share_strerror","curl_strerror","curl_version","dom_import_simplexml","gd_info","imageloadfont","imagesetstyle","imagecreatetruecolor","imageistruecolor","imagetruecolortopalette","imagepalettetotruecolor","imagecolormatch","imagesetthickness","imagefilledellipse","imagefilledarc","imagealphablending","imagesavealpha","imagelayereffect","imagecolorallocatealpha","imagecolorresolvealpha","imagecolorclosestalpha","imagecolorexactalpha","imagecopyresampled","imagerotate","imagesettile","imagesetbrush","imagecreate","imagetypes","imagecreatefromstring","imagecreatefromgif","imagecreatefromjpeg","imagecreatefrompng","imagecreatefromwebp","imagecreatefromxbm","imagecreatefromxpm","imagecreatefromwbmp","imagecreatefromgd","imagecreatefromgd2","imagecreatefromgd2part","imagecreatefrombmp","imagecreatefromtga","imagexbm","imagegif","imagepng","imagewebp","imagejpeg","imagewbmp","imagegd","imagegd2","imagebmp","imagedestroy","imagecolorallocate","imagepalettecopy","imagecolorat","imagecolorclosest","imagecolorclosesthwb","imagecolordeallocate","imagecolorresolve","imagecolorexact","imagecolorset","imagecolorsforindex","imagegammacorrect","imagesetpixel","imageline","imagedashedline","imagerectangle","imagefilledrectangle","imagearc","imageellipse","imagefilltoborder","imagefill","imagecolorstotal","imagecolortransparent","imageinterlace","imagepolygon","imageopenpolygon","imagefilledpolygon","imagefontwidth","imagefontheight","imagechar","imagecharup","imagestring","imagestringup","imagecopy","imagecopymerge","imagecopymergegray","imagecopyresized","imagesx","imagesy","imagesetclip","imagegetclip","imageftbbox","imagefttext","imagettfbbox","imagettftext","imagefilter","imageconvolution","imageflip","imageantialias","imagecrop","imagecropauto","imagescale","imageaffine","imageaffinematrixget","imageaffinematrixconcat","imagegetinterpolation","imagesetinterpolation","imageresolution","mb_language","mb_internal_encoding","mb_http_input","mb_http_output","mb_detect_order","mb_substitute_character","mb_preferred_mime_name","mb_parse_str","mb_output_handler","mb_str_split","mb_strlen","mb_strpos","mb_strrpos","mb_stripos","mb_strripos","mb_strstr","mb_strrchr","mb_stristr","mb_strrichr","mb_substr_count","mb_substr","mb_strcut","mb_strwidth","mb_strimwidth","mb_convert_encoding","mb_convert_case","mb_strtoupper","mb_strtolower","mb_detect_encoding","mb_list_encodings","mb_encoding_aliases","mb_encode_mimeheader","mb_decode_mimeheader","mb_convert_kana","mb_convert_variables","mb_encode_numericentity","mb_decode_numericentity","mb_send_mail","mb_get_info","mb_check_encoding","mb_scrub","mb_ord","mb_chr","mb_regex_encoding","mb_ereg","mb_eregi","mb_ereg_replace","mb_eregi_replace","mb_ereg_replace_callback","mb_split","mb_ereg_match","mb_ereg_search","mb_ereg_search_pos","mb_ereg_search_regs","mb_ereg_search_init","mb_ereg_search_getregs","mb_ereg_search_getpos","mb_ereg_search_setpos","mb_regex_set_options","mysqli_affected_rows","mysqli_autocommit","mysqli_begin_transaction","mysqli_change_user","mysqli_character_set_name","mysqli_close","mysqli_commit","mysqli_connect","mysqli_connect_errno","mysqli_connect_error","mysqli_data_seek","mysqli_dump_debug_info","mysqli_debug","mysqli_errno","mysqli_error","mysqli_error_list","mysqli_stmt_execute","mysqli_execute","mysqli_fetch_field","mysqli_fetch_fields","mysqli_fetch_field_direct","mysqli_fetch_lengths","mysqli_fetch_all","mysqli_fetch_array","mysqli_fetch_assoc","mysqli_fetch_object","mysqli_fetch_row","mysqli_field_count","mysqli_field_seek","mysqli_field_tell","mysqli_free_result","mysqli_get_connection_stats","mysqli_get_client_stats","mysqli_get_charset","mysqli_get_client_info","mysqli_get_client_version","mysqli_get_links_stats","mysqli_get_host_info","mysqli_get_proto_info","mysqli_get_server_info","mysqli_get_server_version","mysqli_get_warnings","mysqli_init","mysqli_info","mysqli_insert_id","mysqli_kill","mysqli_more_results","mysqli_multi_query","mysqli_next_result","mysqli_num_fields","mysqli_num_rows","mysqli_options","mysqli_ping","mysqli_poll","mysqli_prepare","mysqli_report","mysqli_query","mysqli_real_connect","mysqli_real_escape_string","mysqli_real_query","mysqli_reap_async_query","mysqli_release_savepoint","mysqli_rollback","mysqli_savepoint","mysqli_select_db","mysqli_set_charset","mysqli_stmt_affected_rows","mysqli_stmt_attr_get","mysqli_stmt_attr_set","mysqli_stmt_bind_param","mysqli_stmt_bind_result","mysqli_stmt_close","mysqli_stmt_data_seek","mysqli_stmt_errno","mysqli_stmt_error","mysqli_stmt_error_list","mysqli_stmt_fetch","mysqli_stmt_field_count","mysqli_stmt_free_result","mysqli_stmt_get_result","mysqli_stmt_get_warnings","mysqli_stmt_init","mysqli_stmt_insert_id","mysqli_stmt_more_results","mysqli_stmt_next_result","mysqli_stmt_num_rows","mysqli_stmt_param_count","mysqli_stmt_prepare","mysqli_stmt_reset","mysqli_stmt_result_metadata","mysqli_stmt_send_long_data","mysqli_stmt_store_result","mysqli_stmt_sqlstate","mysqli_sqlstate","mysqli_ssl_set","mysqli_stat","mysqli_store_result","mysqli_thread_id","mysqli_thread_safe","mysqli_use_result","mysqli_warning_count","mysqli_refresh","mysqli_escape_string","mysqli_set_opt","use_soap_error_handler","is_soap_fault","xmlrpc_encode","xmlrpc_decode","xmlrpc_decode_request","xmlrpc_encode_request","xmlrpc_get_type","xmlrpc_set_type","xmlrpc_is_fault","xmlrpc_server_create","xmlrpc_server_destroy","xmlrpc_server_register_method","xmlrpc_server_call_method","xmlrpc_parse_method_descriptions","xmlrpc_server_add_introspection_data","xmlrpc_server_register_introspection_callback","xmlwriter_open_uri","xmlwriter_open_memory","xmlwriter_set_indent","xmlwriter_set_indent_string","xmlwriter_start_comment","xmlwriter_end_comment","xmlwriter_start_attribute","xmlwriter_end_attribute","xmlwriter_write_attribute","xmlwriter_start_attribute_ns","xmlwriter_write_attribute_ns","xmlwriter_start_element","xmlwriter_end_element","xmlwriter_full_end_element","xmlwriter_start_element_ns","xmlwriter_write_element","xmlwriter_write_element_ns","xmlwriter_start_pi","xmlwriter_end_pi","xmlwriter_write_pi","xmlwriter_start_cdata","xmlwriter_end_cdata","xmlwriter_write_cdata","xmlwriter_text","xmlwriter_write_raw","xmlwriter_start_document","xmlwriter_end_document","xmlwriter_write_comment","xmlwriter_start_dtd","xmlwriter_end_dtd","xmlwriter_write_dtd","xmlwriter_start_dtd_element","xmlwriter_end_dtd_element","xmlwriter_write_dtd_element","xmlwriter_start_dtd_attlist","xmlwriter_end_dtd_attlist","xmlwriter_write_dtd_attlist","xmlwriter_start_dtd_entity","xmlwriter_end_dtd_entity","xmlwriter_write_dtd_entity","xmlwriter_output_memory","xmlwriter_flush"];
            CodeMirror.hint.javascript = function (editor) {
        var cursor = editor.getCursor();
        var currentLine = editor.getLine(cursor.line);
        var start = cursor.ch;
        var end = start;
        while (end < currentLine.length && /[\w$]+/.test(currentLine.charAt(end))) ++end;
        while (start && /[\w$]+/.test(currentLine.charAt(start - 1))) --start;
        var curWord = start != end && currentLine.slice(start, end);
        var regex = new RegExp('^' + curWord, 'i');
        var result = {
            list: (!curWord ? list : list.filter(function (item) {
                return item.match(regex);
            })).sort(),
            from: CodeMirror.Pos(cursor.line, start),
            to: CodeMirror.Pos(cursor.line, end)
        };
        func_php.forEach(h=>{if (h.startsWith(curWord))  result.list.push(h);});
        result.list.sort();
              return result;
};
             return CodeMirror.Pass;
      }
      function methods(cm, pred) {
           var cur = cm.getCursor();
        if (!pred || pred()) setTimeout(function() {
          if (!cm.state.completionActive)
            cm.showHint({completeSingle: false});
        }, 100);
           var orig = CodeMirror.hint.javascript;
            const list=[""];
            const hintWords = ["dbins(","dbup(","nearest(","redirect_get(","h_site(","p_site(",
              "mbsplit(","water_mark(","searchf(","get(","search(","api(",
              "isvalid(","validate(","is_valid(","ifile_create(","ifile_start(",
              "error(","f_echo_type(","to_obj(","dump_obj(","mask_obj(","mask_to_obj(","ifile_update(",
              "ifile_insert(","ifile_delete(","ifile_drop(","ifile_list(","var_start(","var_inarr(","var_inarr_to(",
              "var_im(","var_ex(","var_method(","var_replace(","var_preplace(","var_search(","var_search_to(",
              "var_input(","var_output(","var_end(","var_count(","ev(","tpl_html(","arr_methods(",
              "include_tpl(","globfunc(","i_unlink(","i_upload(","dir_search_foton(","translate(","up_tpl(",
              "up_core(","del_file(","post_arr(","arr(","require_class(","require_obj(","log_file(","log(","alert(",
              "transactions(","i(","i_h1(","replace_post(","delete_post(","i_update(","abc09(","unsetPost(","i_insert(",
              "i_delete(","i_create(","i_drop(","i_list_ajax(","migrate(","fork(","getServerLoad(","m_json(","mBack(",
              "mDiff(","check_diff(","mCreate(","mUpdate(","mUpdateField(","mAddtr(","mEcho(","mType(","mAlter(",
              "mDel(","mDrop(","mDeltr(","i_arr(","arr_session(","i_handler_ajax(","i_load_ajax(","i_for_ajax(","delete_mvc(",
              "up_mvc(","changeArr(","create_mvc(","i_list(","getid(","getlist(","getXML(","handlersql(","i_arr_all(",
              "i_echo_type(","list_model(","i_alter(","mUpdateField2(","mf_json(","auth(","st_start(","st_end(","s_mvc(",
              "interfaces_chmod(","chmod_section(","include_files(","fexists_foton(","update_core(","up_core_conn(","tpl(",
              "cache_foton(","a_cache_foton(","extracod(","imgcod(","text_resizes_foton(","mobile_foton(","smtpSend(","mail_foton(",
              "glob_controller(","dir_delete_foton(","number_foton(","text_foton(","input_foton(","html_foton(","tr(","meta(",
              "tpl_front_css(","tpl_front_js(","i_front_css(","i_front_js(","m_method(","m_class(","m_name(","m_obj(",
              "sizefile(","chmod_id(","list_files_glob(","list_model_glob(","list_mvc(","isset_file(","copy_dir(","is_format(",
              "arr_sort(","arr_shift(","arr_sort_key(","db(","git(","post_get(","sharding(",
              "isAuth(","select_db(","quotes(","forq(","where(","table(","ip_user(","q(","rand(",
              "countsql(","count(","c(","sql_dump_file(","strukture_foton(","format_table_foton(","site_dump(","field_table_foton(",
              "execute(","insert_db(","q(","insert(","select_db_seo(","select_from_seo(","lim(","sorts(","sql_insert(",
              "no_slash(","htmlret(","update_db(","up(","delete_db(","d(","trun(","id_field_foton(","tablessortw(",
              "table_listdesc(","list_table(","type_column(","is_table(","field_table(","create(","insert_arr(","int(",
              "medium(","date(","bit(","poly(","real(","time(","inc(","key(","us(","back(",
              "cq(","one(","limit(","group(","limita(","drop(","where(","andf(","orf(","sc(",
              "like(","where_arr(","query(","eq(","type_kod(","join(","post_replace("];
              CodeMirror.hint.javascript = function (editor) {
        var cursor = editor.getCursor();
        var currentLine = editor.getLine(cursor.line);
        var start = cursor.ch;
        var end = start;
        while (end < currentLine.length && /[\w$]+/.test(currentLine.charAt(end))) ++end;
        while (start && /[\w$]+/.test(currentLine.charAt(start - 1))) --start;
        var curWord = start != end && currentLine.slice(start, end);
        var regex = new RegExp('^' + curWord, 'i');
        var result = {
            list: (!curWord ? list : list.filter(function (item) {
                return item.match(regex);
            })).sort(),
            from: CodeMirror.Pos(cursor.line, start),
            to: CodeMirror.Pos(cursor.line, end)
        };
        hintWords.forEach(h=>{if (h.startsWith(curWord))  result.list.push(h);});
        result.list.sort();
              return result;
};
             return CodeMirror.Pass;
      }

      function objects(cm, pred) {
          var cur = cm.getCursor();
        if (!pred || pred()) setTimeout(function() {
          if (!cm.state.completionActive)
            cm.showHint({completeSingle: false});
        }, 100);
           var orig = CodeMirror.hint.javascript;
            const list=["введите объект"];
            const hintWords=["core-", "mod-","glob-","widget-","valid-"];
        CodeMirror.hint.javascript = function (editor) {
        var cursor = editor.getCursor();
        var currentLine = editor.getLine(cursor.line);
        var start = cursor.ch;
        var end = start;
        while (end < currentLine.length && /[\w$]+/.test(currentLine.charAt(end))) ++end;
        while (start && /[\w$]+/.test(currentLine.charAt(start - 1))) --start;
        var curWord = start != end && currentLine.slice(start, end);
        var regex = new RegExp('^' + curWord, 'i');
        var result = {
            list: (!curWord ? list : list.filter(function (item) {
                return item.match(regex);
            })).sort(),
            from: CodeMirror.Pos(cursor.line, start),
            to: CodeMirror.Pos(cursor.line, end)
        };
        hintWords.forEach(h=>{if (h.startsWith(curWord))  result.list.push('>'+h);});
        result.list.sort();
              return result;
};
             return CodeMirror.Pass;
}
  
var width_f = $('.td-f').css('width');
width_f = width_f.replace('px','');
var editor<?=$name;?> = CodeMirror.fromTextArea(document.getElementById("<?=$name;?>"), {
            mode: "javascript",
            lineNumbers: true,
            ineWrapping: true,
            foldGutter: true,
            extraKeys: {
                "'-'":objects,
          "'>'": methods,
          "' '": methods_php
        },
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
      });
      editor<?=$name;?>.setSize(width_f, '100%');
function escapeHtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}
  var input<?=$name;?> = document.getElementById("select<?=$name;?>");
  function selectTheme<?=$name;?>() {
    var theme<?=$name;?> = input<?=$name;?>.options[input<?=$name;?>.selectedIndex].textContent;
  
    editor<?=$name;?>.setOption("theme", theme<?=$name;?>);
    location.hash = "#" + theme<?=$name;?>;
  }
  var choice<?=$name;?> = (location.hash && location.hash.slice(1)) ||
               (document.location.search &&
                decodeURIComponent(document.location.search.slice(1)));
  if (choice<?=$name;?>) {
    input<?=$name;?>.value = choice<?=$name;?>;
    editor<?=$name;?>.setOption("theme", choice<?=$name;?>);
  }
  CodeMirror.on(window, "hashchange", function() {
    var theme<?=$name;?> = location.hash.slice(1);
    if (theme<?=$name;?>) { input<?=$name;?>.value = theme<?=$name;?>; selectTheme<?=$name;?>(); }
  });
  $(document).on('mouseover','.saves-f',function(){ 
    editor<?=$name;?>.save();
    var content = editor<?=$name;?>.getValue();
    $(this).parent('.pole-f').find('.codi').html(escapeHtml(content));
});

$(document).on('click','.otk-f',function(){ 
var text = $(this).parent('.pole-f').find('.code-none').val();   
editor<?=$name;?>.setValue(text);
});
</script>

<select onchange="selectTheme<?=$name;?>()" id='select<?=$name;?>' class="select-code">
        <option selected>the-matrix</option>
    <option>default</option>
    <option>3024-day</option>
    <option>3024-night</option>
    <option>abcdef</option>
    <option>ambiance</option>
    <option>base16-dark</option>
    <option>base16-light</option>
    <option>bespin</option>
    <option>blackboard</option>
    <option>cobalt</option>
    <option>colorforth</option>
    <option>darcula</option>
    <option>dracula</option>
    <option>duotone-dark</option>
    <option>duotone-light</option>
    <option>eclipse</option>
    <option>elegant</option>
    <option>erlang-dark</option>
    <option>gruvbox-dark</option>
    <option>hopscotch</option>
    <option>icecoder</option>
    <option>idea</option>
    <option>isotope</option>
    <option>lesser-dark</option>
    <option>liquibyte</option>
    <option>lucario</option>
    <option>material</option>
    <option>mbo</option>
    <option>mdn-like</option>
    <option>midnight</option>
    <option>monokai</option>
    <option>neat</option>
    <option>neo</option>
    <option>night</option>
    <option>nord</option>
    <option>oceanic-next</option>
    <option>panda-syntax</option>
    <option>paraiso-dark</option>
    <option>paraiso-light</option>
    <option>pastel-on-dark</option>
    <option>railscasts</option>
    <option>rubyblue</option>
    <option>seti</option>
    <option>shadowfox</option>
    <option>solarized dark</option>
    <option>solarized light</option>
    <option>tomorrow-night-bright</option>
    <option>tomorrow-night-eighties</option>
    <option>ttcn</option>
    <option>twilight</option>
    <option>vibrant-ink</option>
    <option>xq-dark</option>
    <option>xq-light</option>
    <option>yeti</option>
    <option>yonce</option>
    <option>zenburn</option>
</select>

