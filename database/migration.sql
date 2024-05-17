--
-- PostgreSQL database dump
--

-- Dumped from database version 16.2
-- Dumped by pg_dump version 16.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: delete_subcommments(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.delete_subcommments() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        DELETE FROM comments
        WHERE parent_comment_id = OLD.id AND article_id = OLD.article_id;
    END IF;

    RETURN NULL;
END;
$$;


ALTER FUNCTION public.delete_subcommments() OWNER TO root;

--
-- Name: update_article_comments(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.update_article_comments() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        UPDATE statistics
        SET comments = comments - 1
        WHERE article_id = OLD.article_id;
    ELSE
        UPDATE statistics
        SET comments = comments + 1
        WHERE article_id = NEW.article_id;
    END IF;

    RETURN NULL;
END;
$$;


ALTER FUNCTION public.update_article_comments() OWNER TO root;

--
-- Name: update_article_rating(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.update_article_rating() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        UPDATE statistics
        SET rating = rating - OLD.rating_influence
        WHERE article_id = OLD.article_id AND OLD.parent_comment_id IS null;
    ELSE
        UPDATE statistics
        SET rating = rating + NEW.rating_influence
        WHERE article_id = NEW.article_id AND NEW.parent_comment_id IS null;
    END IF;

    RETURN NULL;
END;
$$;


ALTER FUNCTION public.update_article_rating() OWNER TO root;

--
-- Name: update_comment_rating(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.update_comment_rating() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        UPDATE comments
        SET rating = rating - OLD.rating_influence
        WHERE id = OLD.parent_comment_id AND article_id = OLD.article_id;
    ELSE
        IF NEW.parent_comment_id IS NOT NULL THEN
            UPDATE comments
            SET rating = rating + NEW.rating_influence
            WHERE id = NEW.parent_comment_id AND article_id = NEW.article_id;
        END IF;
    END IF;

    RETURN NULL;
END;
$$;


ALTER FUNCTION public.update_comment_rating() OWNER TO root;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: admins; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.admins (
    nickname character varying(250),
    password character varying(250)
);


ALTER TABLE public.admins OWNER TO root;

--
-- Name: admins_tokens; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.admins_tokens (
    token character varying(250),
    nickname_encrypted character varying(250),
    expiration_time_encrypted character varying(250)
);


ALTER TABLE public.admins_tokens OWNER TO root;

--
-- Name: articles; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.articles (
    id integer NOT NULL,
    version_id integer,
    title character varying(250),
    text text,
    tags text[],
    created_date integer,
    premoderation_status smallint,
    approvededitorially_status smallint,
    editorially_status smallint
);


ALTER TABLE public.articles OWNER TO root;

--
-- Name: codes; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.codes (
    article_id integer NOT NULL,
    view_code text,
    edit_code text
);


ALTER TABLE public.codes OWNER TO root;

--
-- Name: comments; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.comments (
    id integer NOT NULL,
    article_id integer,
    parent_comment_id integer,
    text text,
    rating integer,
    rating_influence integer DEFAULT 0,
    created_date integer
);


ALTER TABLE public.comments OWNER TO root;

--
-- Name: comments_comment_id_seq; Type: SEQUENCE; Schema: public; Owner: root
--

CREATE SEQUENCE public.comments_comment_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.comments_comment_id_seq OWNER TO root;

--
-- Name: comments_comment_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: root
--

ALTER SEQUENCE public.comments_comment_id_seq OWNED BY public.comments.id;


--
-- Name: settings; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.settings (
    name character varying(250),
    value integer
);


ALTER TABLE public.settings OWNER TO root;

--
-- Name: statistics; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.statistics (
    article_id integer NOT NULL,
    rating bigint,
    comments bigint,
    created_date integer,
    edit_timeout_to_date integer,
    current_version integer,
    current_title character varying(250),
    current_text text,
    current_tags text[],
    editorially_status smallint,
    approvededitorially_status smallint,
    premoderation_status smallint
);


ALTER TABLE public.statistics OWNER TO root;

--
-- Name: comments id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_comment_id_seq'::regclass);


--
-- Name: comments delete_subcommments_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER delete_subcommments_trigger AFTER DELETE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.delete_subcommments();


--
-- Name: comments update_article_comments_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_article_comments_trigger AFTER INSERT OR DELETE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.update_article_comments();


--
-- Name: comments update_article_rating_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_article_rating_trigger AFTER INSERT OR DELETE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.update_article_rating();


--
-- Name: comments update_comment_rating_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_comment_rating_trigger AFTER INSERT OR DELETE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.update_comment_rating();


--
-- PostgreSQL database dump complete
--

