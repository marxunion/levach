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
-- Name: pg_trgm; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pg_trgm WITH SCHEMA public;


--
-- Name: EXTENSION pg_trgm; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pg_trgm IS 'text similarity measurement and index searching based on trigrams';


--
-- Name: article_delete_comments(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.article_delete_comments() RETURNS trigger
    LANGUAGE plpgsql
    AS $$BEGIN
    IF TG_OP = 'DELETE' THEN
        DELETE FROM comments
        WHERE article_id = OLD.id;
    END IF;

    RETURN NULL;
END;$$;


ALTER FUNCTION public.article_delete_comments() OWNER TO root;

--
-- Name: comment_delete_subcommments(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.comment_delete_subcommments() RETURNS trigger
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


ALTER FUNCTION public.comment_delete_subcommments() OWNER TO root;

--
-- Name: comment_update_article_comments_count(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.comment_update_article_comments_count() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        UPDATE articles
        SET comments_count = comments_count - 1
        WHERE id = OLD.article_id;
    ELSE
        UPDATE articles
        SET comments_count = comments_count + 1
        WHERE id = NEW.article_id;
    END IF;

    RETURN NULL;
END;
$$;


ALTER FUNCTION public.comment_update_article_comments_count() OWNER TO root;

--
-- Name: comment_update_article_rating(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.comment_update_article_rating() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    IF TG_OP = 'DELETE' THEN
        UPDATE articles
        SET rating = rating - OLD.rating_influence
        WHERE id = OLD.article_id AND OLD.parent_comment_id IS null;
    ELSE
        UPDATE articles
        SET rating = rating + NEW.rating_influence
        WHERE id = NEW.article_id AND NEW.parent_comment_id IS null;
    END IF;

    RETURN NULL;
END;
$$;


ALTER FUNCTION public.comment_update_article_rating() OWNER TO root;

--
-- Name: comment_update_comment_rating(); Type: FUNCTION; Schema: public; Owner: root
--

CREATE FUNCTION public.comment_update_comment_rating() RETURNS trigger
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


ALTER FUNCTION public.comment_update_comment_rating() OWNER TO root;

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
    rating bigint,
    comments_count bigint,
    created_date integer,
    edit_timeout_to_date integer,
    current_version integer,
    current_title character varying(250),
    current_text text,
    current_tags text[],
    editorially_status smallint,
    approvededitorially_status smallint,
    premoderation_status smallint,
    view_code text,
    edit_code text
);


ALTER TABLE public.articles OWNER TO root;

--
-- Name: articles_versions; Type: TABLE; Schema: public; Owner: root
--

CREATE TABLE public.articles_versions (
    article_id integer NOT NULL,
    version_id integer,
    title character varying(250),
    text text,
    tags text[],
    created_date integer,
    premoderation_status smallint,
    approvededitorially_status smallint,
    editorially_status smallint
);


ALTER TABLE public.articles_versions OWNER TO root;

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
-- Name: comments id; Type: DEFAULT; Schema: public; Owner: root
--

ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_comment_id_seq'::regclass);


--
-- Name: idx_articles_approvededitorially_status; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_approvededitorially_status ON public.articles USING btree (approvededitorially_status);


--
-- Name: idx_articles_article_id; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_article_id ON public.articles USING btree (id);


--
-- Name: idx_articles_created_date; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_created_date ON public.articles USING btree (created_date);


--
-- Name: idx_articles_created_date_sort; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_created_date_sort ON public.articles USING btree (created_date DESC);


--
-- Name: idx_articles_current_tags_gin; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_current_tags_gin ON public.articles USING gin (current_tags);


--
-- Name: idx_articles_edit_code; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_edit_code ON public.articles USING btree (edit_code);


--
-- Name: idx_articles_editorially_status; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_editorially_status ON public.articles USING btree (editorially_status);


--
-- Name: idx_articles_premoderation_status; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_premoderation_status ON public.articles USING btree (premoderation_status);


--
-- Name: idx_articles_rating; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_rating ON public.articles USING btree (rating DESC);


--
-- Name: idx_articles_versions_approvededitorially_status; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_versions_approvededitorially_status ON public.articles_versions USING btree (approvededitorially_status);


--
-- Name: idx_articles_versions_articles_id; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_versions_articles_id ON public.articles_versions USING btree (article_id);


--
-- Name: idx_articles_versions_created_date; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_versions_created_date ON public.articles_versions USING btree (created_date);


--
-- Name: idx_articles_versions_editorially_status; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_versions_editorially_status ON public.articles_versions USING btree (editorially_status);


--
-- Name: idx_articles_versions_premoderation_status; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_versions_premoderation_status ON public.articles_versions USING btree (premoderation_status);


--
-- Name: idx_articles_versions_version_id; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_versions_version_id ON public.articles_versions USING btree (version_id);


--
-- Name: idx_articles_view_code; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_articles_view_code ON public.articles USING btree (view_code);


--
-- Name: idx_atricles_current_title_trgm; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_atricles_current_title_trgm ON public.articles USING gin (current_title public.gin_trgm_ops);


--
-- Name: idx_comments_article_id; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_comments_article_id ON public.comments USING btree (article_id);


--
-- Name: idx_comments_created_date; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_comments_created_date ON public.comments USING btree (created_date DESC);


--
-- Name: idx_comments_id; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_comments_id ON public.comments USING btree (id);


--
-- Name: idx_comments_parent_comment_id; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_comments_parent_comment_id ON public.comments USING btree (parent_comment_id);


--
-- Name: idx_comments_rating; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_comments_rating ON public.comments USING btree (rating DESC);


--
-- Name: idx_comments_text_trgm; Type: INDEX; Schema: public; Owner: root
--

CREATE INDEX idx_comments_text_trgm ON public.comments USING gin (text public.gin_trgm_ops);


--
-- Name: articles delete_comments_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER delete_comments_trigger AFTER DELETE ON public.articles FOR EACH ROW EXECUTE FUNCTION public.article_delete_comments();


--
-- Name: comments delete_subcommments_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER delete_subcommments_trigger AFTER DELETE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.comment_delete_subcommments();


--
-- Name: comments update_article_comments_count_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_article_comments_count_trigger AFTER INSERT OR DELETE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.comment_update_article_comments_count();


--
-- Name: comments update_article_rating_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_article_rating_trigger AFTER INSERT OR DELETE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.comment_update_article_rating();


--
-- Name: comments update_comment_rating_trigger; Type: TRIGGER; Schema: public; Owner: root
--

CREATE TRIGGER update_comment_rating_trigger AFTER INSERT OR DELETE ON public.comments FOR EACH ROW EXECUTE FUNCTION public.comment_update_comment_rating();


--
-- PostgreSQL database dump complete
--

