import type { Project } from "../lib/types";
import { Badge } from "./Badge";
import { SectionHeading } from "./SectionHeading";
import { useLocale } from "../i18n/useLocale";
import { temporaryProjectImage } from "../lib/projectImage";
import { FeaturedProject } from "./FeaturedProject";
import { revealDelay } from "../lib/reveal";
import { SectionBackground } from "./SectionBackground";

function ProjectCard({ project, index }: { project: Project; index: number }) {
  const { t } = useLocale();

  return (
    <article
      style={revealDelay(index)}
      className="project-card reveal group flex flex-col overflow-hidden rounded-2xl border border-border bg-surface transition-all hover:-translate-y-1 hover:border-accent/50"
    >
      <div className="relative flex aspect-video items-center justify-center overflow-hidden border-b border-border bg-surface-2">
        <img
          src={project.image_url ?? temporaryProjectImage(project)}
          alt={t("projects.previewAlt", { title: project.title })}
          loading="lazy"
          decoding="async"
          className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
        />
      </div>

      <div className="flex flex-1 flex-col p-6">
        <h3 className="text-lg font-semibold text-heading">
          {project.title}
        </h3>
        {project.subtitle && (
          <p className="mt-1 text-sm text-accent">{project.subtitle}</p>
        )}
        {project.description && (
          <p className="mt-3 text-sm leading-relaxed text-text-dim">
            {project.description}
          </p>
        )}

        {project.bullets.length > 0 && (
          <ul className="mt-4 space-y-1.5 text-sm text-text">
            {project.bullets.map((bullet, i) => (
              <li key={i} className="flex gap-2">
                <span className="mt-2 h-1 w-1 shrink-0 rounded-full bg-text-dim" />
                <span>{bullet}</span>
              </li>
            ))}
          </ul>
        )}

        <div className="mt-5 flex flex-wrap gap-2">
          {project.tech_stack.map((tech) => (
            <Badge key={tech}>{tech}</Badge>
          ))}
        </div>

        <div className="mt-6 flex gap-4 text-sm font-medium">
          {project.demo_url ? (
            <a
              href={project.demo_url}
              target="_blank"
              rel="noreferrer"
              className="text-accent hover:underline"
            >
              {t("projects.liveDemo")}
            </a>
          ) : (
            <span className="text-text-dim">{t("projects.demoSoon")}</span>
          )}
          {project.github_url && (
            <a
              href={project.github_url}
              target="_blank"
              rel="noreferrer"
              className="text-text-dim hover:text-accent"
            >
              {t("projects.source")}
            </a>
          )}
        </div>
      </div>
    </article>
  );
}

export function Projects({
  projects,
  loading,
}: {
  projects: Project[];
  loading: boolean;
}) {
  const { t } = useLocale();
  const featuredProject = projects.find((p) => p.featured);
  const restProjects = featuredProject
    ? projects.filter((p) => p.id !== featuredProject.id)
    : projects;

  return (
    <section
      id="projects"
      className="relative isolate overflow-hidden px-6 py-16 sm:py-20 md:py-24"
    >
      <SectionBackground />
      <div className="mx-auto max-w-6xl">
        <SectionHeading
          eyebrow={t("projects.eyebrow")}
          title={t("projects.title")}
        />

        {loading ? (
          <>
            <div className="mb-12 h-96 animate-pulse rounded-2xl border border-border bg-surface" />
            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
              {Array.from({ length: 3 }).map((_, i) => (
                <div
                  key={i}
                  className="h-96 animate-pulse rounded-2xl border border-border bg-surface"
                />
              ))}
            </div>
          </>
        ) : projects.length === 0 ? (
          <p className="text-center text-text-dim">{t("projects.empty")}</p>
        ) : (
          <>
            {featuredProject && <FeaturedProject project={featuredProject} />}
            {restProjects.length > 0 && (
              <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {restProjects.map((project, i) => (
                  <ProjectCard key={project.id} project={project} index={i} />
                ))}
              </div>
            )}
          </>
        )}
      </div>
    </section>
  );
}
